<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPeternak
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
        // Memeriksa apakah pengguna adalah peternak
        if (auth()->user() && auth()->user()->role !== 'peternak') {
            // Jika bukan peternak, kembalikan response error atau redirect
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
