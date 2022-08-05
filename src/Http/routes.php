<?php

function shopify_controller_action($action)
{
    return '\Woolf\Carter\Http\Controllers\ShopifyController@' . $action;
}

Route::get('signup', [
    'as'   => 'shopify.form.signup',
    'uses' => shopify_controller_action('registerStore')
]);

Route::get('install', [
    'as'   => 'shopify.install',
    'uses' => shopify_controller_action('install')
]);

Route::post('install', [
    'as'   => 'shopify.action.install',
    'uses' => shopify_controller_action('install')
]);

Route::get('register', [
    'as'   => 'shopify.register',
    'uses' => shopify_controller_action('register')
]);

Route::get('activate', [
    'as'   => 'shopify.activate.plan',
    'uses' => shopify_controller_action('activate')
]);

Route::get('login', [
    'as'   => 'shopify.login',
    'uses' => shopify_controller_action('login')
]);

Route::get('dashboard', [
    'as'   => 'shopify.dashboard',
    'uses' => shopify_controller_action('dashboard')
]);