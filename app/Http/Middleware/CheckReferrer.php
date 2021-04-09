<?php

namespace App\Http\Middleware;

use Closure;

class CheckReferrer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->has('ref')) {
            // Store referrer in cookie(for 30 days)
            $cookie = cookie('cardvest_referrer', $request->query('ref'), (60*24*7));

            return $response->withCookie($cookie);
        }

        return $response;
    }
}