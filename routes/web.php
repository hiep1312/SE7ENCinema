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
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/button', Button::class)->name('pages.ui-features.buttons');
    Route::get('/dropdown', Dropdown::class)->name('pages.ui-features.dropdowns');
    Route::get('/typography', Typography::class)->name('pages.ui-features.typography');
    Route::get('/table', Table::class)->name('pages.tables.table');
    Route::get('/basic_element', Basic_element::class)->name('pages.forms.basic_elements');
    Route::get('/chartj', Chartj::class)->name('pages.charts.chartjs');
    Route::get('/icon', Icon::class)->name('pages.icons.mdi');
    Route::get('/blank_page', Blank_page::class)->name('pages.samples.blank-page');
    Route::get('/error-404', Error_404::class)->name('pages.samples.error-404');
    Route::get('/error-500', Error_500::class)->name('pages.samples.error-500');
    Route::get('/login', Login::class)->name('pages.samples.login');
    Route::get('/register', Register::class)->name('pages.samples.register');
});


Route::view('/', 'welcome')->name('welcome');
