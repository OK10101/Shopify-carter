<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;

class RequestHasShopDomain
{

    public function handle($request, Closure $next)
    {
        if (! $request->has('shop')) {
            return redirect()->route('shopify.form.signup');
        }

        return $next($request);
    }
}