<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Genres\GenreCreate;
use App\Livewire\Admin\Genres\GenreEdit;
use App\Livewire\Admin\Genres\GenreIndex;
use App\Livewire\Admin\Genres\GenreShow;
use App\Livewire\Admin\Movies\MovieIndex;
use App\Livewire\Admin\Movies\MovieCreate;
use App\Livewire\Admin\Movies\MovieEdit;
use App\Livewire\Admin\Movies\MovieShow;
use App\Livewire\Admin\Movies\MovieTrash;




Route::prefix('admin')->name('admin.')->group(function () {


    /* Genres */
    Route::prefix('/genres')->name('genres.')->group(function () {
        Route::get('/', GenreIndex::class)->name('index');
        Route::get('/create', GenreCreate::class)->name('create');
        Route::get('/{id}/edit', GenreEdit::class)->name('edit');
        Route::get('/{id}', GenreShow::class)->name('show');
    });

    /* Movies */
    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/test', MovieTrash::class)->name('test');
        Route::get('/', MovieIndex::class)->name('index');
        Route::get('/create', MovieCreate::class)->name('create');
        Route::get('/{movie}/edit', MovieEdit::class)->name('edit');
        Route::get('/{movie}', MovieShow::class)->name('show');
    });


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
