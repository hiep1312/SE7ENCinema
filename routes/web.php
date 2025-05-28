<?php

use App\Livewire\Admin\Room\RoomIndex;
use App\Livewire\Admin\Room\RoomCreate;
use App\Livewire\Admin\Room\RoomEdit;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Template\Dashboard;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/rooms', RoomIndex::class)->name('rooms.index');
    Route::get('/rooms/create', RoomCreate::class)->name('rooms.create');
    Route::get('/rooms/{roomId}/edit', RoomEdit::class)->name('rooms.edit');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});


Route::view('/', 'welcome')->name('welcome');
