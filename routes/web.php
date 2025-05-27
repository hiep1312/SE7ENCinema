<?php

use App\Http\Livewire\Admin\BookingManager;
use App\Http\Livewire\Client\BookingTicket;
use Illuminate\Support\Facades\Route;
Route::get('/test', function () {
    return view('test');
});
// Route::get('/booking/{showtime_id}', function ($showtime_id) {
//     return view('client', ['showtimeId' => $showtime_id]);
// });
use App\Livewire\Client\SelectMovieShowtime;
use App\Livewire\Client\SelectSeats;
use App\Livewire\Client\SelectFood;
use App\Livewire\Client\ConfirmBooking;

Route::get('/booking', SelectMovieShowtime::class)->name('booking.select_showtime');
Route::get('/booking/seats/{showtime_id}', SelectSeats::class)->name('booking.select_seats');
Route::get('/booking/food/{booking_id}', SelectFood::class)->name('booking.select_food');
Route::get('/booking/confirm/{booking_id}', ConfirmBooking::class)->name('booking.confirm');
