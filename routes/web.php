<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdventurerController;


Route::middleware('auth')->controller(AdventurerController::class)->prefix('adventurer/profile')
    ->name('adventurer.')->group(function () {

        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
    });


