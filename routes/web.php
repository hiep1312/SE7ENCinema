<?php

use App\Http\Livewire\Admin\BookingManager;
use App\Http\Livewire\Client\BookingTicket;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return view('test');
});
// Route::get('/booking/{showtime_id}', function ($showtime_id) {
//     return view('client', ['showtimeId' => $showtime_id]);
// });
use App\Livewire\Client\SelectMovieShowtime;
use App\Livewire\Client\SelectSeats;
use App\Livewire\Client\SelectFood;
use App\Livewire\Client\ConfirmBooking;

Route::get('/booking', SelectMovieShowtime::class)->name('booking.select_showtime');
Route::get('/booking/seats/{showtime_id}', SelectSeats::class)->name('booking.select_seats');
Route::get('/booking/food/{booking_id}', SelectFood::class)->name('booking.select_food');
Route::get('/booking/confirm/{booking_id}', ConfirmBooking::class)->name('booking.confirm');

use App\Livewire\Admin\Foods\FoodCreate;
use App\Livewire\Admin\Foods\FoodDetail;
use App\Livewire\Admin\Foods\FoodEdit;
use App\Livewire\Admin\Foods\FoodIndex;
use App\Livewire\Admin\Banners\BannerCreate;
use App\Livewire\Admin\Banners\BannerEdit;
use App\Livewire\Admin\Banners\BannerIndex;
use App\Livewire\Admin\Rooms\RoomCreate;
use App\Livewire\Admin\Rooms\RoomDetail;
use App\Livewire\Admin\Rooms\RoomEdit;
use App\Livewire\Admin\Rooms\RoomIndex;
use App\Livewire\Admin\FoodVariants\FoodVariantIndex;
use App\Livewire\Admin\FoodVariants\FoodVariantDetail;
use App\Livewire\Admin\FoodVariants\FoodVariantCreate;
use App\Livewire\Admin\FoodVariants\FoodVariantEdit;
use App\Livewire\Admin\FoodAttributes\AttributeIndex;
use App\Livewire\Admin\Movies\MovieCreate;
use App\Livewire\Admin\Movies\MovieDetail;
use App\Livewire\Admin\Movies\MovieEdit;
use App\Livewire\Admin\Movies\MovieIndex;
use App\Livewire\Admin\Users\UserCreate;
use App\Livewire\Admin\Users\UserDetail;
use App\Livewire\Admin\Users\UserEdit;
use App\Livewire\Admin\Users\UserIndex;
use App\Livewire\Test;
use App\Livewire\Admin\Ratings\RatingIndex;
use App\Livewire\Admin\Showtimes\ShowtimeCreate;
use App\Livewire\Admin\Showtimes\ShowtimeEdit;
use App\Livewire\Admin\Showtimes\ShowtimeIndex;


use App\Livewire\Payment\VnpayPayment;
use App\Livewire\Booking\BookingFood;

Route::get('/thanh-toan', VnpayPayment::class)->name('thanh-toan');
Route::get('/vnpay-return', [VnpayPayment::class, 'vnpayReturn'])->name('vnpay.return');

Route::prefix('admin')->name('admin.')->group(function () {
    /* Banners */
    Route::prefix('/banners')->name('banners.')->group(function () {
        Route::get('/', BannerIndex::class)->name('index');
        Route::get('/create', BannerCreate::class)->name('create');
        Route::get('/edit/{banner}', BannerEdit::class)->name('edit');
    });

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

    /* Foods Variants */
    Route::prefix('/food-variants')->name('food_variants.')->group(function () {
        Route::get('/', FoodVariantIndex::class)->name('index');
        Route::get('/create', FoodVariantCreate::class)->name('create');
        Route::get('/edit/{variant}', FoodVariantEdit::class)->name('edit');
        Route::get('/detail/{variant}', FoodVariantDetail::class)->name('detail');
    });

    Route::prefix('/food-attributes')->name('food_attributes.')->group(function () {
        Route::get('/', AttributeIndex::class)->name('index');
    });

    /* Users */
    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/', UserIndex::class)->name('index');
        Route::get('/create', UserCreate::class)->name('create');
        Route::get('/edit/{user}', UserEdit::class)->name('edit');
        Route::get('/detail/{user}', UserDetail::class)->name('detail');
    });

    /* Rating */
    Route::prefix('/ratings')->name('ratings.')->group(function () {
        Route::get('/', RatingIndex::class)->name('index');
    });

    /* Movies */
    Route::prefix('/movies')->name('movies.')->group(function () {
        Route::get('/', MovieIndex::class)->name('index');
        Route::get('/create', MovieCreate::class)->name('create');
        Route::get('/edit/{movie}', MovieEdit::class)->name('edit');
        Route::get('/detail/{movie}', MovieDetail::class)->name('detail');
    });

    /* Showtimes */
    Route::prefix('/showtimes')->name('showtimes.')->group(function () {
        Route::get('/', ShowtimeIndex::class)->name('index');
        Route::get('/create', ShowtimeCreate::class)->name('create');
        Route::get('/edit/{showtime}', ShowtimeEdit::class)->name('edit');
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




Route::get('/booking-food', BookingFood::class);

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
Route::get('/test', Test::class);
