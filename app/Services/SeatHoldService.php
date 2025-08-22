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
    const HOLD_DURATION_MINUTES = 10;
    const MAX_TEMP_BANS = 3;
    const MAX_PERM_BANS = 5;
    const BAN_DURATION_HOURS = 24;

    public function checkUserBan($userId)
    {
        if (!$userId) return false;

        $user = User::find($userId);
        if ($user && $user->status === 'banned') {
            return true;
        }

        $violationCount = UserViolation::where('user_id', $userId)
            ->where('violation_type', 'seat_timeout')
            ->where('occurred_at', '>=', now()->subHours(self::BAN_DURATION_HOURS))
            ->count();

        if ($violationCount >= self::MAX_PERM_BANS) {
            User::where('id', $userId)->update(['status' => 'banned']);
            return true;
        }

        return $violationCount >= self::MAX_TEMP_BANS;
    }

    public function getBanInfo($userId)
    {
        if (!$userId) return null;

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

        $violationCount = UserViolation::where('user_id', $userId)
            ->where('violation_type', 'seat_timeout')
            ->count();

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
            $latestViolation = UserViolation::where('user_id', $userId)
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

    public function holdSeats($showtimeId, $seatIds, $userId)
    {
        if ($this->checkUserBan($userId)) {
            $banInfo = $this->getBanInfo($userId);
            throw new \Exception($banInfo['reason'] . ': ' . $banInfo['details']);
        }

        DB::beginTransaction();
        try {
            SeatHold::where('expires_at', '<', now())->update(['status' => 'expired']);

            $now = now();
            $existingHold = SeatHold::where('user_id', $userId)
                ->where('showtime_id', $showtimeId)
                ->where('status', 'holding')
                ->where('expires_at', '>', $now)
                ->first();

            if ($existingHold) {
                SeatHold::where('user_id', $userId)
                    ->where('showtime_id', $showtimeId)
                    ->where('status', 'holding')
                    ->update(['status' => 'released']);
            } else {
                SeatHold::where('user_id', $userId)
                    ->where('status', 'holding')
                    ->update(['status' => 'released']);
            }

            $expiresAt = $existingHold ? $existingHold->expires_at : $now->copy()->addMinutes(self::HOLD_DURATION_MINUTES);

            $conflictingHolds = SeatHold::whereIn('seat_id', $seatIds)
                ->where('showtime_id', $showtimeId)
                ->where('user_id', '!=', $userId)
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
                throw new \Exception('Một số ghế đã được đặt !');
            }

            foreach ($seatIds as $seatId) {
                SeatHold::create([
                    'showtime_id' => $showtimeId,
                    'seat_id' => $seatId,
                    'user_id' => $userId,
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

    public function releaseHolds($userId)
    {
        return SeatHold::where('user_id', $userId)
            ->where('status', 'holding')
            ->update(['status' => 'released']);
    }

    public function getHeldSeats($showtimeId, $excludeUserId = null)
    {
        SeatHold::where('expires_at', '<', now())->update(['status' => 'expired']);

        $query = SeatHold::where('showtime_id', $showtimeId)
            ->where('status', 'holding')
            ->where('expires_at', '>', now())
            ->with('seat');

        if ($excludeUserId) {
            $query->where('user_id', '!=', $excludeUserId);
        }

        return $query->get()->map(function ($hold) {
            return [
                'seat_id' => $hold->seat_id,
                'seat_code' => $hold->seat->seat_row . $hold->seat->seat_number,
                'user_id' => $hold->user_id,
                'expires_at' => $hold->expires_at,
                'remaining_seconds' => max(0, Carbon::parse($hold->expires_at)->diffInSeconds(now()))
            ];
        });
    }

    public function handleExpiredHolds($userId)
    {
        UserViolation::create([
            'user_id' => $userId,
            'violation_type' => 'seat_timeout',
            'violation_details' => 'User failed to complete booking within time limit',
            'occurred_at' => now()
        ]);

        $violationCount = UserViolation::where('user_id', $userId)
            ->where('violation_type', 'seat_timeout')
            ->where('occurred_at', '>=', now()->subHours(self::BAN_DURATION_HOURS))
            ->count();

        if ($violationCount >= self::MAX_PERM_BANS) {
            User::where('id', $userId)->update(['status' => 'banned']);
        }

        $this->releaseHolds($userId);

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

    public function getUserHoldStatus($showtimeId, $userId)
    {
        $holds = SeatHold::where('showtime_id', $showtimeId)
            ->where('user_id', $userId)
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
