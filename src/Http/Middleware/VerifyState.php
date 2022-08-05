<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;

class VerifyState
{
    public function handle($request, Closure $next)
    {
        $state = $request->session()->pull('state');

        if (! strlen($state) || $state !== $request->input('state')) {
            app()->abort(403, 'Client Error: 403');
        }

        return $next($request);
    }
}