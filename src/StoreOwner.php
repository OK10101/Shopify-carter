<?php

namespace Woolf\Carter;

use \Crypt;

trait StoreOwner
{
    public function encryptAccessToken()
    {
        $this->access_token = Crypt::encrypt($this->access_token);
    }

    protected function decryptAccessToken(array $attributes)
    {
        if (isset($attributes['access_token'])) {
            $attributes['access_token'] = Crypt::decrypt($attributes['access_token']);
        }

        return $attributes;
    }
}