<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class DebugLogin
{
    public function handle($request, Closure $next)
    {

            $user = \App\Models\User::first();
            if ($user) {
                Auth::login($user);
            }

        return $next($request);
    }
}
