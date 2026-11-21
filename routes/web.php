<?php

use App\Http\Controllers\ActivityOrganizerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AdminOrganizerController;
use App\Http\Controllers\AdminFraudReportController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AdventureController;
use App\Http\Controllers\AdventureSchedulesController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;




// Organizer Profile & Dashboard (Slice 1)
Route::middleware(['auth'])->group(function () {
    Route::resource('organizers', ActivityOrganizerController::class)
        ->only(['index', 'store', 'update']);
});

Route::get('/organizer/dashboard', [ActivityOrganizerController::class, 'dashboard'])
    ->middleware(['auth', 'approved.organizer'])
    ->name('organizer.dashboard');

// Admin Verification & Revenue Dashboard (Slices 2 & 3)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::get('/reports', [AdminFraudReportController::class, 'index'])->name('reports.index');
        Route::patch('/reports/{review}/suspend', [AdminFraudReportController::class, 'suspendAdventure'])->name('reports.suspend');
        Route::patch('/reports/{review}/dismiss', [AdminFraudReportController::class, 'dismissReport'])->name('reports.dismiss');
        Route::resource('organizers', AdminOrganizerController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::patch('organizers/{organizer}/profile', [AdminOrganizerController::class, 'updateProfile'])
            ->whereNumber('organizer')->name('organizers.profile.update');
        Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class)
            ->only(['index', 'show']);
        Route::where(['user' => '[0-9]+', 'adventure' => '[0-9]+', 'booking' => '[0-9]+', 'review' => '[0-9]+'])
            ->group(function () {
                Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except('destroy');
                Route::resource('adventures', \App\Http\Controllers\Admin\AdventureController::class)->except('destroy');
                Route::patch('adventures/{adventure}/suspend', [\App\Http\Controllers\Admin\AdventureController::class, 'suspend'])->name('adventures.suspend');
                Route::resource('bookings', \App\Http\Controllers\Admin\BookingController::class)->only(['index', 'show', 'edit', 'update']);
                Route::patch('bookings/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])->name('bookings.cancel');
                Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
            });
    });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('bookings', BookingController::class)
    ->middleware(['auth', 'can:viewAny,App\Models\Booking']);

require __DIR__ . '/auth.php';
use App\Http\Controllers\AdventurerController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GuidesController;
use App\Http\Controllers\GuidesAvailabilitiesController;



Route::get('/', [HomeController::class, 'home'])->name('home');

Route::get('/home/adventures', [AdventureController::class, 'indexAll'])
    ->name('adventuresAll.index');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/adventures/{adventure}/profile', [AdventureController::class, 'showProfile'])
    ->name('adventures.profile');
Route::get('/adventures', [AdventureController::class, 'index'])->name('adventures.index');


Route::middleware(['auth', 'approved.organizer'])->group(function () {
    Route::resource('guides', GuidesController::class)
        ->only(['index', 'create', 'store', 'update']);
    Route::resource('guide-availabilities', GuidesAvailabilitiesController::class);
    Route::resource('adventures', AdventureController::class)->except('index');
    Route::post(
        '/adventure-schedules/{adventure_schedules}/days/generate',
        [AdventureSchedulesController::class, 'generateDays']
    )->name('adventure-schedules.days.generate');
    Route::resource('adventure-schedules', AdventureSchedulesController::class)
        ->parameters(['adventure-schedules' => 'adventure_schedules']);
    Route::put(
        '/adventure-schedules/{adventure_schedules}/days/{adventure_day}',
        [AdventureSchedulesController::class, 'updateDay']
    )->name('adventure-schedules.days.update');
    Route::post('/schedules', [AdventureSchedulesController::class, 'store'])
        ->name('schedules.store');
    Route::post('/adventure-images', [ImagesController::class, 'store'])
        ->name('images.store');
    Route::put('/adventure-images/{image}', [ImagesController::class, 'update'])
        ->name('images.update');
    Route::put('/schedules/{adventure_schedules}', [AdventureSchedulesController::class, 'update'])
        ->name('schedules.update');

});

Route::middleware('auth')->controller(AdventurerController::class)->prefix('adventurer/profile')
    ->name('adventurer.')->group(function () {

        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
    });



Route::controller(ReviewController::class)
    ->prefix('adventures/{adventure}/reviews')
    ->name('reviews.')
    ->group(function () {

        Route::get('/', 'index')
            ->name('index');

        Route::get('/create', 'create')
            ->middleware('auth')
            ->name('create');

        Route::post('/', 'store')
            ->middleware('auth')
            ->name('store');

        Route::get('/{review}', 'show')
            ->name('show');

        Route::get('/{review}/edit', 'edit')
            ->middleware('auth')
            ->name('edit');

        Route::patch('/{review}', 'update')
            ->middleware('auth')
            ->name('update');

        Route::delete('/{review}', 'destroy')
            ->middleware('auth')
            ->name('destroy');

        Route::post('/{review}/report', 'report')
            ->middleware('auth')
            ->name('report');

    });

Route::middleware('auth')->group(function () {
    Route::resource('payments', PaymentController::class);
    Route::get('/bookings/{bookingId}/payments', [PaymentController::class, 'showByBooking'])
        ->whereNumber('bookingId')
        ->name('payments.byBooking');
});
// Frontend preview - local development only
if (app()->environment('local')) {
    Route::view('/ui-preview', 'pages.ui-preview')
        ->name('ui.preview');
}

// Home Page Preview - Local Development Only
if (app()->environment('local')) {
    Route::view('/home-preview', 'pages.home')
        ->name('home.preview');
}

// Dashboard UI preview - local environment only.
if (app()->environment('local')) {
    Route::view(
        '/dashboard-preview',
        'pages.dashboard-preview'
    )->name('dashboard.preview');
}

// Auth UI Preview - Local Environment Only

if (app()->environment('local')) {

    Route::view(
        '/auth-preview',
        'pages.auth-preview'
    )->name('auth.preview');

}

Route::post('/chatbot', [ChatbotController::class, 'chat'])
    ->middleware('auth')
    ->name('chatbot.chat');
