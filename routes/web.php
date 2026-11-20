<?php

use App\Http\Controllers\ActivityOrganizerController;
use App\Http\Controllers\AdminOrganizerController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
