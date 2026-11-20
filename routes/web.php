<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityOrganizerController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/organizer/dashboard', [ActivityOrganizerController::class, 'dashboard'])->name('organizer.dashboard');
});

use Illuminate\Support\Facades\Auth;

// TEMPORARY TEST ROUTES — delete before pushing your PR
Route::get('/test-login/{id}', function ($id) {
    Auth::loginUsingId($id);

    return auth()->user()->isAdmin()
        ? redirect()->route('admin.organizers.index')
        : redirect()->route('organizer.dashboard');
});

Route::get('/test-approved', function () {
    return 'Access granted! You are an approved organizer.';
})->middleware(['auth', 'approved.organizer']);
