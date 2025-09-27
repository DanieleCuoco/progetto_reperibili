<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReperibileAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('reperibile')->check()) {
            return redirect()->route('reperibile.login');
        }

        return $next($request);
    }
}