<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityOrganizerController;
use App\Http\Controllers\AdminOrganizerController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Route::get('/organizer/dashboard', [ActivityOrganizerController::class, 'dashboard'])->name('organizer.dashboard');
    Route::middleware(['auth'])->group(function () {
    Route::resource('organizers', ActivityOrganizerController::class)
        ->only(['index', 'store', 'update']);
    });
});

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('organizers', AdminOrganizerController::class)
            ->only(['index', 'update', 'destroy']);
    });

// use Illuminate\Support\Facades\Auth;

// TEMPORARY TEST ROUTES — delete before pushing your PR
// Route::get('/test-login/{id}', function ($id) {
//     Auth::loginUsingId($id);

//     return auth()->user()->isAdmin()
//         ? redirect()->route('admin.organizers.index')
//         : redirect()->route('organizer.dashboard');
// });

Route::get('/test-login/{id}', function ($id) {
    $user = \App\Models\User::findOrFail($id);
    \Illuminate\Support\Facades\Auth::login($user);

    return $user->isAdmin()
        ? redirect('/admin/organizers')
        : redirect()->route('organizers.index');
});

Route::get('/test-approved', function () {
    return 'Access granted! You are an approved organizer.';
})->middleware(['auth', 'approved.organizer']);

// TEMPORARY LOCAL AUTH — DELETE BEFORE FINAL PR
Route::get('/login', fn () => response('Unauthenticated. Visit /test-login/{id} first.', 401))
    ->name('login');
