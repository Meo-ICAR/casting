<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\CastingSearch;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialAuthController;

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

Route::get('/dashboard', function () {
    // Reindirizza l'utente dove preferisci, es. alla ricerca casting
    return redirect()->route('casting.search');
})->middleware(['auth', 'verified'])->name('dashboard'); // <--- Il nome che mancava

// Logout route (per link/bottoni che usano route('logout'))
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/profile/{profile}/roles', function (App\Models\Profile $profile) {
    return app(\App\Filament\Resources\ProfileResource\Pages\ProfileRoles::class, [
        'record' => $profile->id
    ])->render();
})->name('profile.roles');

// Social Login Routes
Route::get('/login/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.login');
Route::get('/login/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
