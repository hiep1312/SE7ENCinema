<?php

namespace App\Services;

use App\Models\SeatHold;
use App\Models\UserViolation;
use App\Models\User;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeatHoldService
{
    const HOLD_DURATION_MINUTES = 0.2;
    const MAX_TEMP_BANS = 3;
    const MAX_PERM_BANS = 5;
    const BAN_DURATION_HOURS = 0.01;

    public function checkUserBan($sessionId, $userIp, $userId = null)
    {
        if ($userId) {
            $user = User::find($userId);
            if ($user && $user->status === 'banned') {
                return true;
            }
        }

        $violationCount = UserViolation::countViolations($sessionId, $userIp, 'seat_timeout', self::BAN_DURATION_HOURS);

        if ($violationCount >= self::MAX_PERM_BANS) {
            if ($userId) {
                User::where('id', $userId)->update(['status' => 'banned']);
            }
            return true;
        }

        return $violationCount >= self::MAX_TEMP_BANS;
    }


    public function getBanInfo($sessionId, $userIp, $userId = null)
    {
        if ($userId) {
            $user = User::find($userId);
            if ($user && $user->status === 'banned') {
                return [
                    'type' => 'user_banned',
                    'reason' => 'Tài khoản đã bị khóa vĩnh viễn',
                    'details' => 'Tài khoản của bạn đã bị khóa do vi phạm quá nhiều lần',
                    'banned_until' => null,
                    'violation_count' => null
                ];
            }
        }

        $violationCount = UserViolation::countViolations($sessionId, $userIp, 'seat_timeout');

        if ($violationCount >= self::MAX_PERM_BANS) {
            return [
                'type' => 'user_banned',
                'reason' => 'Tài khoản đã bị khóa vĩnh viễn',
                'details' => "Bạn đã để hết thời gian giữ ghế {$violationCount} lần, vượt quá giới hạn cho phép.",
                'banned_until' => null,
                'violation_count' => $violationCount
            ];
        }

        if ($violationCount >= self::MAX_TEMP_BANS) {
            $latestViolation = UserViolation::where(function ($q) use ($sessionId, $userIp) {
                $q->where('session_id', $sessionId)->orWhere('user_ip', $userIp);
            })
                ->where('violation_type', 'seat_timeout')
                ->latest('occurred_at')
                ->first();

            return [
                'type' => 'temporary_ban',
                'reason' => 'Tạm khóa do vi phạm quá nhiều lần',
                'details' => "Bạn đã để hết thời gian giữ ghế {$violationCount} lần trong " . self::BAN_DURATION_HOURS . " giờ qua.",
                'banned_until' => $latestViolation ? $latestViolation->occurred_at->addHours(self::BAN_DURATION_HOURS) : now()->addHours(self::BAN_DURATION_HOURS),
                'violation_count' => $violationCount
            ];
        }

        return null;
    }


    public function holdSeats($showtimeId, $seatIds, $sessionId, $userIp, $userId = null)
    {
        if ($this->checkUserBan($sessionId, $userIp, $userId)) {
            $banInfo = $this->getBanInfo($sessionId, $userIp, $userId);
            throw new \Exception($banInfo['reason'] . ': ' . $banInfo['details']);
        }

        DB::beginTransaction();
        try {
            SeatHold::cleanupExpired();

            $now = now();

            $existingHold = SeatHold::where('session_id', $sessionId)
                ->where('showtime_id', $showtimeId)
                ->where('status', 'holding')
                ->where('expires_at', '>', $now)
                ->first();

            if ($existingHold) {
                SeatHold::where('session_id', $sessionId)
                    ->where('showtime_id', $showtimeId)
                    ->where('status', 'holding')
                    ->update(['status' => 'released']);
            } else {
                SeatHold::releaseHoldsBySession($sessionId);
            }

            $expiresAt = $existingHold ? $existingHold->expires_at : $now->copy()->addMinutes(self::HOLD_DURATION_MINUTES);

            $conflictingHolds = SeatHold::whereIn('seat_id', $seatIds)
                ->where('showtime_id', $showtimeId)
                ->where('session_id', '!=', $sessionId)
                ->where('status', 'holding')
                ->where('expires_at', '>', $now)
                ->exists();

            if ($conflictingHolds) {
                throw new \Exception('Một số ghế đang được giữ bởi người khác.');
            }

            $bookedSeats = DB::table('booking_seats')
                ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->whereIn('booking_seats.seat_id', $seatIds)
                ->where('bookings.showtime_id', $showtimeId)
                ->whereIn('bookings.status', ['paid'])
                ->exists();

            if ($bookedSeats) {
                throw new \Exception('Một số ghế đã được đặt.');
            }

            foreach ($seatIds as $seatId) {
                SeatHold::create([
                    'showtime_id' => $showtimeId,
                    'seat_id' => $seatId,
                    'session_id' => $sessionId,
                    'user_ip' => $userIp,
                    'held_at' => $now,
                    'expires_at' => $expiresAt,
                    'status' => 'holding'
                ]);
            }

            DB::commit();
            return $expiresAt;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function releaseHolds($sessionId)
    {
        return SeatHold::releaseHoldsBySession($sessionId);
    }

    public function getHeldSeats($showtimeId, $excludeSessionId = null)
    {
        SeatHold::cleanupExpired();

        return SeatHold::getActiveHolds($showtimeId, $excludeSessionId)
            ->map(function ($hold) {
                return [
                    'seat_id' => $hold->seat_id,
                    'seat_code' => $hold->seat->seat_row . $hold->seat->seat_number,
                    'session_id' => $hold->session_id,
                    'expires_at' => $hold->expires_at,
                    'remaining_seconds' => $hold->remaining_time
                ];
            });
    }

    public function handleExpiredHolds($sessionId, $userIp, $userId = null)
    {
        UserViolation::addViolation(
            $sessionId,
            $userIp,
            'seat_timeout',
            'User failed to complete booking within time limit'
        );

        $violationCount = UserViolation::countViolations($sessionId, $userIp, 'seat_timeout', self::BAN_DURATION_HOURS);

        if ($userId) {
            if ($violationCount >= self::MAX_PERM_BANS) {
                User::where('id', $userId)->update(['status' => 'banned']);
            }
        }

        $this->releaseHolds($sessionId);

        $banned = $violationCount >= self::MAX_PERM_BANS;
        $tempBanned = !$banned && $violationCount >= self::MAX_TEMP_BANS;

        return [
            'banned' => $banned,
            'temporary_banned' => $tempBanned,
            'violation_count' => $violationCount,
            'ban_until' => $tempBanned ? now()->addHours(self::BAN_DURATION_HOURS) : ($banned ? null : null),
            'warning' => $violationCount == (self::MAX_TEMP_BANS - 1) || $violationCount == (self::MAX_PERM_BANS - 1)
        ];
    }

    public function getUserHoldStatus($showtimeId, $sessionId)
    {
        $holds = SeatHold::where('showtime_id', $showtimeId)
            ->where('session_id', $sessionId)
            ->where('status', 'holding')
            ->where('expires_at', '>', now())
            ->with('seat')
            ->get();

        if ($holds->isEmpty()) {
            return null;
        }

        $earliestExpiry = $holds->min('expires_at');
        $seatCodes = $holds->map(function ($hold) {
            return $hold->seat->seat_row . $hold->seat->seat_number;
        })->toArray();

        $expiryTime = Carbon::parse($earliestExpiry);
        $remainingSeconds = $expiryTime->diffInSeconds(now());

        if ($expiryTime <= now()) {
            $remainingSeconds = 0;
        }

        return [
            'seat_codes' => $seatCodes,
            'expires_at' => $earliestExpiry,
            'remaining_seconds' => $remainingSeconds,
            'remaining_minutes' => ceil($remainingSeconds / 60)
        ];
    }

    public function isHoldExpired($expiresAt)
    {
        return Carbon::parse($expiresAt) <= now();
    }
}
