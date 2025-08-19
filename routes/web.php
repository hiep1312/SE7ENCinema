<?php

use App\Livewire\Admin\Foods\FoodCreate;
use App\Livewire\Admin\Foods\FoodDetail;
use App\Livewire\Admin\Foods\FoodEdit;
use App\Livewire\Admin\Foods\FoodIndex;
use App\Livewire\Admin\Banners\BannerCreate;
use App\Livewire\Admin\Banners\BannerEdit;
use App\Livewire\Admin\Banners\BannerIndex;
use App\Livewire\Admin\Bookings\BookingDetail;
use App\Livewire\Admin\Bookings\BookingIndex;
use App\Livewire\Admin\FoodAttributes\FoodAttributeIndex;
use App\Livewire\Client\MovieBooking\MovieBooking;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Rooms\RoomCreate;
use App\Livewire\Admin\Rooms\RoomDetail;
use App\Livewire\Admin\Rooms\RoomEdit;
use App\Livewire\Admin\Rooms\RoomIndex;
use App\Livewire\Admin\FoodVariants\FoodVariantIndex;
use App\Livewire\Admin\FoodVariants\FoodVariantDetail;
use App\Livewire\Admin\FoodVariants\FoodVariantCreate;
use App\Livewire\Admin\FoodVariants\FoodVariantEdit;
use App\Livewire\Admin\Genres\GenreCreate;
use App\Livewire\Admin\Genres\GenreEdit;
use App\Livewire\Admin\Genres\GenreIndex;
use App\Livewire\Admin\Movies\MovieCreate;
use App\Livewire\Admin\Movies\MovieDetail;
use App\Livewire\Admin\Movies\MovieEdit;
use App\Livewire\Admin\Movies\MovieIndex;
use App\Livewire\Admin\Notifications\NotificationCreate;
use App\Livewire\Admin\Notifications\NotificationDetail;
use App\Livewire\Admin\Notifications\NotificationIndex;
use App\Livewire\Admin\Promotions\PromotionCreate;
use App\Livewire\Admin\Promotions\PromotionDetail;
use App\Livewire\Admin\Promotions\PromotionEdit;
use App\Livewire\Admin\Promotions\PromotionIndex;
use App\Livewire\Admin\Users\UserCreate;
use App\Livewire\Admin\Users\UserDetail;
use App\Livewire\Admin\Users\UserEdit;
use App\Livewire\Admin\Users\UserIndex;
use App\Livewire\Admin\Ratings\RatingIndex;
use App\Livewire\Admin\Scanner\Index as ScannerIndex;
use App\Livewire\Admin\Showtimes\ShowtimeCreate;
use App\Livewire\Admin\Showtimes\ShowtimeEdit;
use App\Livewire\Admin\Showtimes\ShowtimeIndex;
use App\Livewire\Client\Showtime\ShowtimeIndex as ShowtimeIndexClient;
use App\Livewire\Client\Promotions\PromotionIndex as PromotionIndexClient;
use App\Livewire\Admin\Tickets\TicketIndex;
use App\Livewire\Client\MovieList;
use App\Livewire\Client\Notifications\Allnotifications;
use App\Livewire\Client\Notifications\NotificationIndex as NotificationIndexClient;
use App\Livewire\Client\Ticket\Index as TicketIndexClient;
use App\Livewire\Client\ClientMovieDetail;
use App\Livewire\Client\User\UserConfirm;
use App\Livewire\Client\User\UserInformation;
use App\Http\Controllers\VnpayController;
use App\Http\Livewire\Admin\BookingManager;
use App\Http\Livewire\Client\BookingTicket;
use App\Livewire\Admin\DasboardChart\Dashboard;
use App\Livewire\Client\SelectMovieShowtime;
use App\Livewire\Client\SelectSeats;
use App\Livewire\Client\SelectFood;
use App\Livewire\Client\ConfirmBooking;
use App\Livewire\Payment\VnpayPayment;
use App\Livewire\Booking\BookingFood;
// use App\Livewire\Client\Bookings\BookingFood;
use App\Livewire\Client\Bookings\BookingPayment;
use App\Livewire\Client\User\BookingDetail as UserBookingDetail;

Route::prefix('admin')->name('admin.')->middleware('auth', 'role:staff,admin')->group(function () {
    /* Dashboard */
    Route::prefix('/dashboards')->name('dashboards.')->group(function () {
        Route::get('/', Dashboard::class)->name('index');
    });

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

    /* Food Variants */
    Route::prefix('/food-variants')->name('food_variants.')->group(function () {
        Route::get('/', FoodVariantIndex::class)->name('index');
        Route::get('/create', FoodVariantCreate::class)->name('create');
        Route::get('/edit/{variant}', FoodVariantEdit::class)->name('edit');
        Route::get('/detail/{variant}', FoodVariantDetail::class)->name('detail');
    });

    /* Food Attributes */
    Route::prefix('/food-attributes')->name('food_attributes.')->group(function () {
        Route::get('/', FoodAttributeIndex::class)->name('index');
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

    /* Bookings */
    Route::prefix('/bookings')->name('bookings.')->group(function () {
        Route::get('/', BookingIndex::class)->name('index');
        Route::get('/detail/{booking}', BookingDetail::class)->name('detail');
    });

    /* Genres */
    Route::prefix('/genres')->name('genres.')->group(function () {
        Route::get('/', GenreIndex::class)->name('index');
        Route::get('/create', GenreCreate::class)->name('create');
        Route::get('/edit/{genre}', GenreEdit::class)->name('edit');
    });

    /* Tickets */
    Route::prefix('/tickets')->name('tickets.')->group(function () {
        Route::get('/', TicketIndex::class)->name('index');
    });

    /* Notifications */
    Route::prefix('/notifications')->name('notifications.')->group(function () {
        Route::get('/', NotificationIndex::class)->name('index');
        Route::get('/create', NotificationCreate::class)->name('create');
        Route::get('/detail/{notification}', NotificationDetail::class)->name('detail');
    });

    /* Scanner */
    Route::get('/scanner/{type}', ScannerIndex::class)
        ->whereIn('type', ['bookings', 'tickets'])
        ->name('scanner');

    /* Promotions */
    Route::prefix('/promotions')->name('promotions.')->group(function () {
        Route::get('/', PromotionIndex::class)->name('index');
        Route::get('/create', PromotionCreate::class)->name('create');
        Route::get('/edit/{promotion}', PromotionEdit::class)->name('edit');
        Route::get('/detail/{promotion}', PromotionDetail::class)->name('detail');
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
    Route::get('/ticket/{bookingCode}/{index?}', TicketIndexClient::class)->name('ticket')
        ->whereAlphaNumeric('bookingCode')->whereNumber('index')
        ->middleware('auth', 'role:staff,admin');

    Route::get('/movie-list', MovieList::class)->name('movies.index');
    Route::get('/booking', SelectMovieShowtime::class)->name('booking.select_showtime');
    Route::get('/booking/seats/{showtime_id}', SelectSeats::class)->name('booking.select_seats');
    Route::get('/booking/food/{booking_id}', SelectFood::class)->name('booking.select_food');
    Route::get('/booking/confirm/{booking_id}', ConfirmBooking::class)->name('booking.confirm');
    Route::get('/booking-food', BookingFood::class);
    Route::view('/', 'livewire.client.template.index')->name('index');
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
    Route::prefix('/notifications')->name('notifications.')->group(function () {
        Route::get('/', NotificationIndexClient::class)->name('index');
        Route::get('allnotification', Allnotifications::class)->name('allnotification');
    });
    /* Lichchieu */
    Route::prefix('/showtimes')->name('showtimes.')->group(function () {
        Route::get('/', ShowtimeIndexClient::class)->name('index');
    });

    /* Chi tiết lịch chiếu từng phim */
    Route::prefix('/movieBooking')->name('movieBooking.')->group(function () {
        Route::get('{movie_id}/', MovieBooking::class)->name('movie');
    });

    Route::view('/privacy-policy', 'livewire.client.template.abouts.privacy_policy')->name('privacy_policy');
    Route::view('/terms-of-service', 'livewire.client.template.abouts.terms_of_service')->name('terms_of_service');
    Route::get('/movies/{movie}', ClientMovieDetail::class)->name('movie_detail');
    /* Promotions */
    Route::get('/promotions', PromotionIndexClient::class)->name('promotions.index');
    Route::get('/user-info/{tab?}', UserInformation::class)->name('userInfo')->middleware('auth');
    Route::get('/user-confirm', UserConfirm::class)->name('userConfirm')->middleware('auth');
    Route::get('/booking-detail/{booking}', UserBookingDetail::class)->name('userBooking')->middleware('auth');
    Route::get('/thanh-toan/{booking_id}', VnpayPayment::class)->name('thanh-toan');
    Route::get('/vnpay-return', [VnpayController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::get('/booking-food', BookingFood::class);
    Route::view('/faq', 'livewire.client.template.abouts.faq')->name('faq');

    /* Bookings */
    /* Route::prefix('/booking')->name('booking.')->group(function () {
        Route::get('/food', BookingFood::class)->name('food');
        Route::get('/payment', BookingPayment::class)->name('payment');
    }); */
});

// Route::view('/admintest', 'clienttest')->name('welcome');
// Route::view('/clienttest', 'clienttest')->name('clienttest');
