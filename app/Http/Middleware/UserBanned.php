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
        
        return $next($request);
    }
}
