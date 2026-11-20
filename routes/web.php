<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
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

    Route::resource('payments', PaymentController::class)->middleware('auth');

    Route::resource('guides', GuidesController::class);

    Route::resource('guide-availabilities', GuidesAvailabilitiesController::class);


    Route::get('/bookings/{bookingId}/payments',
    [PaymentController::class, 'showByBooking']
    )->name('payments.byBooking');