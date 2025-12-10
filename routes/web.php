<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('profiles', \App\Http\Controllers\ProfileController::class);
Route::post('profiles/{profile}/toggle-visibility', [\App\Http\Controllers\ProfileController::class, 'toggleVisibility'])
    ->name('profiles.toggle-visibility');
Route::get('profiles-search', [\App\Http\Controllers\ProfileController::class, 'search'])
    ->name('profiles.search');
