<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Check if this is an API request or web request
            if ($request->is('api/*')) {
                return route('api.unauthorized');
            }

            // For web requests, redirect to the appropriate login route
           if ($request->is('admin*')) {
              return route('filament.admin.auth.login');
           }

            // For other routes, use the default login
            return route('login');
        }

        return null;
    }
}
