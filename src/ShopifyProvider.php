<?php

namespace Woolf\Carter;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class ShopifyProvider
{

    private $client;
    private $app;
    private $token;

    public function __construct(Application $app, ShopifyClient $client)
    {
        $this->app = $app;
        $this->client = $client;

        $this->client->domain(($user = $this->user()) ? $user->domain : $this->request()->input('shop'));
    }

    protected function user()
    {
        return $this->app['auth']->user();
    }

    protected function request()
    {
        return $this->app['request'];
    }

    public function authorize($returnUrl)
    {
        $this->session()->set('state', $this->newState());

        return $this->client->redirect($this->authorizeEndpoint($returnUrl));
    }

    protected function session()
    {
        return $this->request()->session();
    }

    protected function newState()
    {
        return Str::random(40);
    }

    protected function authorizeEndpoint($returnUrl)
    {
        return $this->client->endpoint('/admin/oauth/authorize', $this->authorizeParameters($returnUrl));
    }

    protected function authorizeParameters($returnUrl)
    {
        return [
            'client_id'    => $this->config('client_id'),
            'redirect_uri' => $returnUrl,
            'scope'        => $this->formattedScopes(),
            'state'        => $this->session()->get('state')
        ];
    }

    protected function config($key)
    {
        return $this->app['config']->get('carter.shopify.' . $key);
    }

    protected function formattedScopes()
    {
        return implode(',', $this->config('scopes'));
    }

    public function store()
    {
        $response = $this->client->get($this->storeEndpoint(), $this->tokenHeader());

        return $this->parseResponse($response->getBody(), 'shop') + ['access_token' => $this->accessToken()];
    }

    protected function storeEndpoint()
    {
        return $this->client->endpoint('/admin/shop.json');
    }

    public function tokenHeader()
    {
        return ['headers' => ['X-Shopify-Access-Token' => $this->accessToken()]];
    }

    protected function accessToken()
    {
        if (is_null($this->token)) {
            $this->token = ($user = $this->user()) ? $user->access_token : $this->requestAccessToken();
        }

        return $this->token;
    }

    public function requestAccessToken()
    {
        $response = $this->client->post($this->accessTokenEndpoint(), $this->accessTokenParameters());

        return $this->parseResponse($response->getBody(), 'access_token');
    }

    protected function accessTokenEndpoint()
    {
        return $this->client->endpoint('/admin/oauth/access_token');
    }

    protected function accessTokenParameters()
    {
        return [
            'headers'     => ['Accept' => 'application/json'],
            'form_params' => [
                'client_id'     => $this->config('client_id'),
                'client_secret' => $this->config('client_secret'),
                'code'          => $this->request()->input('code'),
            ]
        ];
    }

    protected function parseResponse($body, $return = false)
    {
        $response = json_decode($body, true);

        if ($return) {
            return (isset($response[$return])) ? $response[$return] : false;
        }

        return $response;
    }

    public function charge()
    {
        $response = $this->client->post($this->chargeEndpoint(), $this->chargeParameters());

        $charge = $this->parseResponse($response->getBody(), 'recurring_application_charge');

        return $this->client->redirect($charge['confirmation_url']);
    }

    protected function chargeEndpoint()
    {
        return $this->client->endpoint('/admin/recurring_application_charges.json');
    }

    protected function chargeParameters()
    {
        return array_merge($this->tokenHeader(), [
            'form_params' => ['recurring_application_charge' => $this->config('plan')]
        ]);
    }

    public function getCharge($id)
    {
        $response = $this->client->get($this->getChargeEndpoint($id), $this->tokenHeader());

        return $this->parseResponse($response->getBody(), 'recurring_application_charge');
    }

    protected function getChargeEndpoint($id)
    {
        return $this->client->endpoint("/admin/recurring_application_charges/{$id}.json");
    }

    public function apps()
    {
        return $this->client->redirect($this->client->endpoint('/admin/apps'));
    }

    public function activate($chargeId)
    {
        $response = $this->client->post($this->activateEndpoint($chargeId),
            array_merge($this->tokenHeader(), $this->activateParameters($chargeId))
        );

        return $response->getStatusCode();
    }

    protected function activateEndpoint($chargeId)
    {
        return $this->client->endpoint("/admin/recurring_application_charges/{$chargeId}/activate.json");
    }

    protected function activateParameters($chargeId)
    {
        return ['form_params' => ['recurring_application_charge' => $chargeId]];
    }
}