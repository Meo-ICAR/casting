<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CastingSearch;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});
Route::resource('profiles', \App\Http\Controllers\ProfileController::class);
Route::post('profiles/{profile}/toggle-visibility', [\App\Http\Controllers\ProfileController::class, 'toggleVisibility'])
    ->name('profiles.toggle-visibility');
Route::get('profiles-search', [\App\Http\Controllers\ProfileController::class, 'search'])
    ->name('profiles.search');



Route::get('/casting/search', CastingSearch::class)
    ->middleware(['auth']) // Proteggilo se vuoi che sia solo per iscritti
    ->name('casting.search');

// Esempio URL: /talent/mario-rossi
Route::get('/talent/{profile:slug}', [ProfileController::class, 'show'])
    ->name('profile.show');
