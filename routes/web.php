<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdventurerController;
use App\Http\Controllers\ReviewController;


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