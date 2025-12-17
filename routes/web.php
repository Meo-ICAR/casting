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

// Esempio URL: /talent/mario-rossi
Route::get('/talent/{profile:slug}', [ProfileController::class, 'show'])
    ->name('profile.show');

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

// Pagina Privacy Policy
Route::view('/privacy', 'privacy')->name('privacy');

// Pagina Termini e Condizioni
Route::view('/terms', 'terms')->name('terms');

// Pagina Termini e Condizioni
Route::view('/cookie', 'cookie')->name('cookie');
/*
Route::get('/admin/login', function () {
    return redirect()->route('/'); // or any other route you want to redirect to
})->middleware('debug.login');
*/
