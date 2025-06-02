<?php

use App\Livewire\Admin\Food\FoodCreate;
use App\Livewire\Admin\Food\FoodDelete;
use App\Livewire\Admin\Food\FoodDetail;
use App\Livewire\Admin\Food\FoodEdit;
use App\Livewire\Admin\Food\FoodList;

use Illuminate\Support\Facades\Route;



Route::prefix('admin')->name('admin.')->group(function () {

    /* Template */
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

Route::prefix('admin/food')->group(function () {

    Route::get('/', FoodList::class)->name('admin.food.list');
    Route::get('/create', FoodCreate::class)->name('admin.food.create');
    Route::get('delete/{id}', [FoodDelete::class, 'delete'])->name('admin.food.delete');
    Route::get('/detail/{id}', FoodDetail::class)->name('admin.food.detail');
    Route::get('/edit/{id}', FoodEdit::class)->name('admin.food.edit');
});


Route::view('/', 'welcome')->name('welcome');
