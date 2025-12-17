<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next): Response
{
    if (Auth::check()) {
        $user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        return match($user->role) {
            UserRole::ADMIN => redirect()->route('filament.admin.pages.dashboard'),
            UserRole::DIRECTOR => redirect()->route('filament.director.pages.dashboard'),
            UserRole::ACTOR => redirect()->route('filament.actor.pages.dashboard'),
            UserRole::HOST => redirect()->route('filament.host.pages.dashboard'),
            UserRole::SERVICER => redirect()->route('filament.servicer.pages.dashboard'),
            default => redirect(filament()->getUrl()),
        };
    }
    return $next($request);
}
}
