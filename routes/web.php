<?php

use App\Livewire\Admin\Room\RoomEdit;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Room\RoomIndex;
use App\Livewire\Admin\Template\Table;
use App\Livewire\Admin\Room\RoomCreate;
use App\Livewire\Admin\Room\RoomDetail;
use App\Livewire\Admin\Template\Button;
use App\Livewire\Admin\Template\Chartj;
use App\Livewire\Admin\Template\Dropdown;
use App\Livewire\Admin\Template\Dashboard;
use App\Livewire\Admin\Template\Typography;
use App\Livewire\Admin\Template\Basic_element;
use App\Livewire\Admin\Template\Blank_page;
use App\Livewire\Admin\Template\Error_404;
use App\Livewire\Admin\Template\Error_500;
use App\Livewire\Admin\Template\Icon;
use App\Livewire\Admin\Template\Login;
use App\Livewire\Admin\Template\Register;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/rooms', RoomIndex::class)->name('rooms.index');
    Route::get('/rooms/create', RoomCreate::class)->name('rooms.create');
    Route::get('/rooms/{roomId}/edit', RoomEdit::class)->name('rooms.edit');
    Route::get('/rooms/{roomId}/detail', RoomDetail::class)->name('rooms.detail');
    Route::view('/', '/welcome');
});


Route::view('/', 'welcome')->name('welcome');
