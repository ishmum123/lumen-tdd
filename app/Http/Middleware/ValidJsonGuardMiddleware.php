<?php

namespace App\Http\Middleware;

use Closure;

class ValidJsonGuardMiddleware
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
        if ($request->all() == null) 
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Invalid JSON Structure'
                ]
            ], 422);


        return $next($request);
    }
}
