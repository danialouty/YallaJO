<?php

use App\Http\Controllers\ActivityOrganizerController;
use App\Http\Controllers\AdminOrganizerController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdventureController;
use App\Http\Controllers\AdventureDayController;
use App\Http\Controllers\AdventureSchedulesController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;




Route::get('/', function () {
    return view('welcome');
});

// Organizer Profile & Dashboard (Slice 1)
Route::middleware(['auth'])->group(function () {
    Route::resource('organizers', ActivityOrganizerController::class)
        ->only(['index', 'store', 'update']);
});

// Admin Verification & Revenue Dashboard (Slices 2 & 3)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('organizers', AdminOrganizerController::class)
            ->only(['index', 'update', 'destroy']);
    });
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('bookings', BookingController::class)->middleware('auth');

require __DIR__.'/auth.php';
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


Route::middleware('auth')->group(function () {
    Route::resource('adventures', AdventureController::class);
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

    });

    Route::resource('payments', PaymentController::class)
        ->except(['create'])
        ->middleware('auth');
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
    Route::resource('payments', PaymentController::class)->middleware('auth');

    Route::resource('guides', GuidesController::class)
        ->only(['index', 'store', 'update'])
        ->middleware(['auth', 'approved.organizer']);

    Route::resource('guide-availabilities', GuidesAvailabilitiesController::class)
        ->middleware(['auth', 'approved.organizer']);


    Route::get('/bookings/{bookingId}/payments',
    [PaymentController::class, 'showByBooking']
    )->name('payments.byBooking');


    Route::post('/chatbot', ['App\\Http\\Controllers\\ChatbotController', 'chat'])
    ->middleware('auth')
    ->name('chatbot.chat');
