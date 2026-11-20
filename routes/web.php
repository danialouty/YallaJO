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

// =========================================================================
// TEMPORARY LOCAL TEST ROUTES — DELETE BEFORE FINAL PR
// =========================================================================
Route::get('/test-login/{id}', function ($id) {
    $user = User::findOrFail($id);
    Auth::login($user);

    return $user->isAdmin()
        ? redirect()->route('admin.organizers.index')
        : redirect()->route('organizers.index');
});

Route::get('/test-approved', function () {
    return 'Access granted! You are an approved organizer.';
})->middleware(['auth', 'approved.organizer']);

Route::get('/login', fn () => response('Unauthenticated. Visit /test-login/{id} first.', 401))
    ->name('login');
