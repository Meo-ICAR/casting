<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Failed;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    Event::listen(Failed::class, function (Failed $event) {
        \Log::warning('Login fallito per: ' . $event->credentials['email'], [
            'user_exists' => (bool) $event->user,
        ]);
    });
}
}
