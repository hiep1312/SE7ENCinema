<?php

use App\Livewire\Admin\showtime\ShowtimeManagement;
use App\Livewire\Admin\Showtime\ShowtimeCreate;
use App\Livewire\Admin\Showtime\ShowtimeUpdate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/showtime', ShowtimeManagement::class)->name('manage.showtimes');
Route::get('/showtime/create', ShowtimeCreate::class)->name('showtime.create');
Route::get('/showtime/update/{id}', ShowtimeUpdate::class)->name('showtime.update');
