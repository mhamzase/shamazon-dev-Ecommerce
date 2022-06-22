<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (auth()->check() && auth()->user()->hasRole(UserType::getTypeName(UserType::VENDOR))) {
            return redirect()->route('vendor.dashboard');
        }elseif (auth()->check() && auth()->user()->hasRole(UserType::getTypeName(UserType::BUYER))) {
            return redirect()->route('home');
        }
        return $next($request);
    }
}
