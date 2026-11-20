<?php

use App\Http\Controllers\AdventureController;
use App\Http\Controllers\AdventureSchedulesController;
use App\Http\Controllers\ImagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('adventures',AdventureController::class);
Route::post('/schedules', [AdventureSchedulesController::class, 'store'])
    ->name('schedules.store');
Route::post('/images', [ImagesController::class, 'store'])
    ->name('images.store');