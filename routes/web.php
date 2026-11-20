<?php

use App\Http\Controllers\AdventureController;
use App\Http\Controllers\AdventureSchedulesController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('adventures',AdventureController::class);
Route::post('/schedules', [AdventureSchedulesController::class, 'store'])
    ->name('schedules.store');
Route::post('/adventure-images', [ImagesController::class, 'store'])
    ->name('images.store');
Route::put('/adventure-images/{image}', [ImagesController::class, 'update'])
    ->name('images.update');
Route::put('/schedules/{adventure_schedules}', [AdventureSchedulesController::class, 'update'])
    ->name('schedules.update');
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/home/adventures', [AdventureController::class, 'indexAll'])
    ->name('adventuresAll.index');
Route::get('/adventures/{adventure}/profile', [AdventureController::class, 'showProfile'])
    ->name('adventures.profile');
