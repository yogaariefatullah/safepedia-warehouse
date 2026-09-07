<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TwoFactorMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (!$request->user()) {
            abort(401);
        }

        if (!$request->session()->get('2fa_verified', false)) {
            return redirect()->route('2fa.challenge');
        }

        return $next($request);
    }
}
