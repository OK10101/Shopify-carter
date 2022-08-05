<?php

namespace Woolf\Carter;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ShopifyClient
{

    private $domain;

    public function domain($domain)
    {
        $this->domain = $domain;
    }

    public function endpoint($path, array $query = [])
    {
        $url = 'https://' . $this->domain . $path;

        if (! empty($query)) {
            $url .= '?' . http_build_query($query, '', '&');
        }

        return $url;
    }

    public function redirect($url)
    {
        return new RedirectResponse($url);
    }

    public function get($url, array $options = [])
    {
        return $this->client()->get($url, $options);
    }

    protected function client()
    {
        return new Client();
    }

    public function post($url, array $options = [])
    {
        return $this->client()->post($url, $options);
    }
}
