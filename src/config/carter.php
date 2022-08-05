<?php

return [

    'shopify' => [

        /*
         *  https://docs.shopify.com/api/authentication/oauth#get-the-client-redentials
         */
        'client_id'     => env('SHOPIFY_KEY'),
        'client_secret' => env('SHOPIFY_SECRET'),

        /*
         *  https://docs.shopify.com/api/authentication/oauth#scopes
         */
        'scopes' => [
            'read_content', 'read_themes', 'read_products', 'read_customers',
            'read_orders', 'read_script_tags', 'read_fulfillments', 'read_shipping'
        ],

        /*
         *  https://docs.shopify.com/api/recurringapplicationcharge#create
         */
        'plan' => [
            'name'       => '',
            'price'      => 0.00,
            'return_url' => '',
            'trial_day'  => 0,
            'test'       => true
        ]
    ]

];