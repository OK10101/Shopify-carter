<?php

use Woolf\Carter\ShopifyClient;
use Woolf\Carter\Tests\TestCase;

class ShopifyClientTest extends TestCase
{

    public function test_it_returns_shopify_api_endpoint_for_store()
    {
        $shopify = new ShopifyClient();
        $shopify->domain('foo.bar');

        $this->assertEquals(
            'https://foo.bar/admin/shop.json',
            $shopify->endpoint('/admin/shop.json')
        );

        $this->assertEquals(
            'https://foo.bar/admin/products.json?baz=qux&this=that',
            $shopify->endpoint('/admin/products.json', ['baz' => 'qux', 'this' => 'that'])
        );
    }
}