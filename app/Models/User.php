<?php

namespace App\Models;
use App\Notifications\ScResetPassword;
use App\Notifications\ScVerifyEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    protected $fillable = [
        'email',
        'password',
        'name',
        'phone',
        'address',
        'avatar',
        'birthday',
        'gender',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'birthday' => 'date',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new ScVerifyEmail);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ScResetPassword($token, $this));
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function releaseHolds(): bool
    {
        return SeatHold::releaseHoldsByUser($this);
    }

    public function countViolations(string|null $type = null, Carbon|int|string $start = 1, Carbon|string|null $end = null): int
    {
        return UserViolation::countViolations($this, $type, $start, $end);
    }

    public function addViolation(string $type, ?string $details = null): bool
    {
        return UserViolation::addViolation($this, $type, $details);
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function scopeStaff($query)
    {
        return $query->whereIn('role', ['admin', 'staff']);
    }

    /**
     * Scope để lấy regular users
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user')->orWhereNull('role');
    }
}
