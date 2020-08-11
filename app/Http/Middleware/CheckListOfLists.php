<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckListOfLists
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->list < 2) {
            return redirect()->route('lists.show',  ['list' => 3]);
        }
        return $next($request);
    }
}
