<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'showtime_id',
        'booking_code',
        'total_price',
        'transaction_code',
        'start_transaction',
        'end_transaction',
        'status',
        'payment_method',
    ];

    protected $casts = [
        'start_transaction' => 'datetime',
        'end_transaction' => 'datetime',
        'total_price' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seats')->withPivot('ticket_price');
    }

    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    public function promotionUsage()
    {
        return $this->hasOne(PromotionUsage::class);
    }

    public function foodOrderItems()
    {
        return $this->hasMany(FoodOrderItem::class);
    }

    protected function getTicketsAttribute()
    {
        return BookingSeat::with('ticket')->where('booking_id', $this->id)->get()->map(fn($bookingSeat) => $bookingSeat->ticket);
    }
}
