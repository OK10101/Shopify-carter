<?php

namespace Woolf\Carter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class RegistersStore
{
    CONST HTTP_OK = 200;

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function register()
    {
        $store = $this->shopify()->store();

        $user = $this->user()->create([
            'name'         => $store['name'],
            'email'        => $store['email'],
            'password'     => bcrypt(Str::random(10)),
            'domain'       => $store['domain'],
            'shopify_id'   => $store['id'],
            'access_token' => $store['access_token']
        ]);

        $this->login($user->fresh());

        return $this;
    }

    protected function shopify()
    {
        return $this->app->make(ShopifyProvider::class);
    }

    protected function user()
    {
        return $this->auth()->user() ?: $this->app->make($this->app['config']->get('auth.model'));
    }

    protected function auth()
    {
        return $this->app['auth'];
    }

    public function login($user)
    {
        return $this->auth()->login($user);
    }

    public function charge()
    {
        return $this->shopify()->charge();
    }

    public function activate($chargeId)
    {
        if ($this->shopify()->activate($chargeId) !== static::HTTP_OK) {
            throw new \Exception();
        }

        $this->user()->update(['charge_id' => $chargeId]);
    }

    public function hasAcceptedCharge($chargeId)
    {
        $charge = $this->shopify()->getCharge($chargeId);

        $acceptable = ['accepted', 'active'];

        return in_array($charge['status'], $acceptable);
    }

}