<?php

use App\Livewire\Admin\Login;
use App\Livewire\Admin\ShowtimeManagement;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', Login::class)->name('login');
Route::middleware(['auth', 'role:admin,staff'])->group(function () {
    Route::get('/quan-ly/suat-chieu', ShowtimeManagement::class)->name('manage.showtimes');
});
