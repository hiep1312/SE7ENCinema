<?php

use Illuminate\Support\Facades\Route;



Route::prefix('admin')->name('admin.')->group(function () {
    // return view('admin.user.index');

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


Route::view('/', 'welcome')->name('welcome');
