<?php

use App\Livewire\Admin\Foods\FoodCreate;
use App\Livewire\Admin\Foods\FoodDetail;
use App\Livewire\Admin\Foods\FoodEdit;
use App\Livewire\Admin\Foods\FoodIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Rooms\RoomCreate;
use App\Livewire\Admin\Rooms\RoomDetail;
use App\Livewire\Admin\Rooms\RoomEdit;
use App\Livewire\Admin\Rooms\RoomIndex;
use App\Livewire\Admin\Genres\GenreCreate;
use App\Livewire\Admin\Genres\GenreEdit;
use App\Livewire\Admin\Genres\GenreIndex;
use App\Livewire\Admin\Genres\GenreShow;
use App\Livewire\Admin\Movies\MovieCreate;
use App\Livewire\Admin\Movies\MovieEdit;
use App\Livewire\Admin\Movies\MovieIndex;
use App\Livewire\Admin\Movies\MovieShow;
use App\Livewire\Admin\Movies\MovieTrash;



Route::prefix('admin')->name('admin.')->group(function () {
    /* Rooms */
    Route::prefix('/rooms')->name('rooms.')->group(function () {
        Route::get('/', RoomIndex::class)->name('index');
        Route::get('/create', RoomCreate::class)->name('create');
        Route::get('/edit/{room}', RoomEdit::class)->name('edit');
        Route::get('/detail/{room}', RoomDetail::class)->name('detail');
    });

    /* Foods */
    Route::prefix('/foods')->name('foods.')->group(function () {
        Route::get('/', FoodIndex::class)->name('index');
        Route::get('/create', FoodCreate::class)->name('create');
        Route::get('/edit/{food}', FoodEdit::class)->name('edit');
        Route::get('/detail/{food}', FoodDetail::class)->name('detail');
    });

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

Route::name('client.')->group(function () {
    Route::view('/home', 'livewire.client.template.index')->name('index');
    Route::view('/blog_category', 'livewire.client.template.blogs.blog_category')->name('blog_category');
    Route::view('/blog_single', 'livewire.client.template.blogs.blog_single')->name('blog_single');
    Route::view('/movie_booking', 'livewire.client.template.movies.movie_booking')->name('movie_booking');
    Route::view('/movie_category', 'livewire.client.template.movies.movie_category')->name('movie_category');
    Route::view('/movie_single_second', 'livewire.client.template.movies.movie_single_second')->name('movie_single_second');
    Route::view('/movie_single', 'livewire.client.template.movies.movie_single')->name('movie_single');
    Route::view('/event_category', 'livewire.client.template.events.event_category')->name('event_category');
    Route::view('/event_single', 'livewire.client.template.events.event_single')->name('event_single');
    Route::view('/about', 'livewire.client.template.abouts.about')->name('about');
    Route::view('/gallery', 'livewire.client.template.gallerys.gallery')->name('gallery');
    Route::view('/booking_type', 'livewire.client.template.bookings.booking_type')->name('booking_type');
    Route::view('/seat_booking', 'livewire.client.template.bookings.seat_booking')->name('seat_booking');
    Route::view('/contact', 'livewire.client.template.contact')->name('contact');
    Route::view('/confirmation_screen', 'livewire.client.template.confirmation_screen')->name('confirmation_screen');
});


Route::view('/', 'welcome')->name('welcome');
Route::view('/clienttest', 'clienttest')->name('clienttest');
