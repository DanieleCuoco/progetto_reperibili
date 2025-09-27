<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .
               "img-src 'self' data:; " .
               "font-src 'self' https://cdn.jsdelivr.net; " .
               "connect-src 'self'";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        return $response;
    }
}
