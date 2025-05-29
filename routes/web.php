<?php

use App\Livewire\Admin\Room\RoomEdit;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Room\RoomIndex;
use App\Livewire\Admin\Room\RoomCreate;
use App\Livewire\Admin\Room\RoomDetail;


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/rooms', RoomIndex::class)->name('rooms.index');
    Route::get('/rooms/create', RoomCreate::class)->name('rooms.create');
    Route::get('/rooms/{roomId}/edit', RoomEdit::class)->name('rooms.edit');
    Route::get('/rooms/{roomId}/detail', RoomDetail::class)->name('rooms.detail');
    Route::view('/dashboard', 'livewire.admin.template.dashboard')->name('dashboard');
    Route::view('/buttons', 'livewire.admin.template.ui-features.buttons')->name('buttons');
    Route::view('/dropdowns', 'livewire.admin.template.ui-features.dropdowns')->name('dropdowns');
    Route::view('/typography', 'livewire.admin.template.ui-features.typography')->name('typography');
    Route::view('/table', 'livewire.admin.template.tables.table')->name('table');
    Route::view('/blank-page', 'livewire.admin.template.samples.blank-page')->name('blank-page');
    Route::view('/basic-elements', 'livewire.admin.template.forms.basic_elements')->name('basic_elements');
    Route::view('/charts', 'livewire.admin.template.charts.chartjs')->name('chartjs');
    Route::view('/icons', 'livewire.admin.template.icons.mdi')->name('mdi');
    Route::view('/error-404', 'livewire.admin.template.samples.error-404')->name('error-404');
    Route::view('/error-500', 'livewire.admin.template.samples.error-500')->name('error-500');
    Route::view('/login', 'livewire.admin.template.samples.login')->name('login');
    Route::view('/register', 'livewire.admin.template.samples.register')->name('register');
});

Route::prefix('client')->name('client.')->group(function (){
    Route::view('/index', 'livewire.client.template.index')->name('index');
    Route::view('/blog_category', 'livewire.client.template.blogs.blog_category')->name('blog_category');
    Route::view('/blog_single', 'livewire.client.template.blogs.blog_single')->name('blog_single');

});


Route::view('/', 'welcome')->name('welcome');
Route::view('/clienttest', 'clienttest')->name('clienttest');
