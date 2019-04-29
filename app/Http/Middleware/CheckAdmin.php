<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
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
      if(auth()->check()){
        if(auth()->user()->isAdmin())
          return $next($request);
      }

      return response([
      'data' => "This Route Is Private",
      'status' => 'error'
    ],401);
    }
}
