<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->trashed() || Auth::user()->available == 0)) {
            // Allow logout and the banned page itself
            if (! $request->is('logout') && ! $request->is('banned')) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Your account has been disabled.'], 403);
                }

                return redirect()->route('banned');
            }
        }

        return $next($request);
    }
}
