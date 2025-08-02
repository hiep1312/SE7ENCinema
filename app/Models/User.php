<?php

namespace App\Models;
use App\Notifications\ScResetPassword;
use App\Notifications\ScVerifyEmail;
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
        'banned_at',
        'ban_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    /**
     * Ban user
     */
    public function ban(string $reason = null): void
    {
        $this->update([
            'status' => 'banned',
            'banned_at' => now(),
            'ban_reason' => $reason
        ]);
    }

    /**
     * Unban user
     */
    public function unban(): void
    {
        $this->update([
            'status' => 'active',
            'banned_at' => null,
            'ban_reason' => null
        ]);
    }

    /**
     * Scope cho user bị ban
     */
    public function scopeBanned($query)
    {
        return $query->where('status', 'banned');
    }

    /**
     * Scope cho user active
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get ban info
     */
    public function getBanInfo(): ?array
    {
        if (!$this->isBanned()) {
            return null;
        }

        return [
            'banned_at' => $this->banned_at,
            'ban_reason' => $this->ban_reason ?: 'Vi phạm quy định hệ thống'
        ];
    }
}
