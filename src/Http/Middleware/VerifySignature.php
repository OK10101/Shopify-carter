<?php

namespace Woolf\Carter\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;

class VerifySignature
{

    private $config;
    private $request;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    public function handle($request, Closure $next)
    {
        $this->request = $request;

        if (! $this->hasValidHmac() || ! $this->hasValidShop()) {
            app()->abort(403, 'Client Error: 403');
        }

        return $next($request);
    }

    public function hasValidHmac()
    {
        $arguments = Collection::make($this->request->except(['signature', 'hmac']));

        $message = $arguments->map(function ($value, $key) {
            return $key . '=' . $value;
        })->sort()->implode('&');

        $hash = hash_hmac($this->getHashingAlgorithm(), $message, $this->config->get('carter.shopify.client_secret'));

        return ($hash === $this->request->get('hmac'));
    }

    protected function getHashingAlgorithm()
    {
        return 'sha256';
    }

    public function hasValidShop()
    {
        return preg_match($this->getValidShopPattern(), $this->request->get('shop'));
    }

    protected function getValidShopPattern()
    {
        return '/^([a-z]|[0-9]|\.|-)+myshopify.com$/i';
    }
}