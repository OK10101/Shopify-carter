<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Woolf\Carter\RegistersStore;

class VerifyChargeAccepted
{
    private $store;
    private $auth;

    public function __construct(RegistersStore $store, Guard $auth)
    {
        $this->store = $store;
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $user = $this->auth->user();

        if (! $user->charge_id || ! $this->store->hasAcceptedCharge($user->charge_id)) {
            return view('carter::shopify.auth.charge', ['redirect' => $this->store->charge()->getTargetUrl()]);
        }

        return $next($request);
    }
}