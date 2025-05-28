<?php

use App\Livewire\Admin\Room\RoomEdit;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Room\RoomIndex;
use App\Livewire\Admin\Room\RoomCreate;
use App\Livewire\Admin\Template\Button;
use App\Livewire\Admin\Template\Dropdown;
use App\Livewire\Admin\Template\Dashboard;
use App\Livewire\Admin\Template\Table;
use App\Livewire\Admin\Template\Typography;
use App\Livewire\Admin\Template\Basic_element;
use App\Livewire\Admin\Template\Chartj;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/rooms', RoomIndex::class)->name('rooms.index');
    Route::get('/rooms/create', RoomCreate::class)->name('rooms.create');
    Route::get('/rooms/{roomId}/edit', RoomEdit::class)->name('rooms.edit');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/button', Button::class)->name('pages.ui-features.buttons');
    Route::get('/dropdown', Dropdown::class)->name('pages.ui-features.dropdowns');
    Route::get('/typography', Typography::class)->name('pages.ui-features.typography');
    Route::get('/table', Table::class)->name('pages.tables.table');
    Route::get('/basic_element', Basic_element::class)->name('pages.forms.basic_elements');
    Route::get('/chartj', Chartj::class)->name('pages.charts.chartjs');
});


Route::view('/', 'welcome')->name('welcome');
