<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is banned
        if (auth()->user()->isBanned()) {
            return redirect()->route('banned');
        }
 
        // Redirect users to the landing page
        if (!auth()->user()->hasRole(['admin', 'super admin'])) {
            return redirect()->away('https://cardvest.ng');
        }

        return $next($request);
    }
}
