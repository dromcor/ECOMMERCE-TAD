<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCartSession
{
    public function handle(Request $request, Closure $next)
    {
        // Ensure a session token exists for guest carts
        if (! $request->hasCookie('cart_session')) {
            $token = bin2hex(random_bytes(12));
            cookie()->queue(cookie('cart_session', $token, 60 * 24 * 30)); // 30 days
        }

        return $next($request);
    }
}
