<?php

namespace Woolf\Carter;

use Illuminate\Support\ServiceProvider;

class CarterServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/Http/routes.php';
        }

        $this->loadViewsFrom(__DIR__ . '/views', 'carter');

        $this->publishes([
            __DIR__ . '/views' => base_path('resources/views/vendor/carter'),
        ]);

        $this->publishes([
            __DIR__ . '/config/carter.php' => config_path('carter.php')
        ], 'config');

        call_user_func([$this->app['config']->get('auth.model'), 'saving'], function ($user) {
            $user->encryptAccessToken();
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/carter.php', 'carter');

        $this->app->singleton('command.carter.table', function ($app) {
            return new CarterTableCommand();
        });

        $this->commands('command.carter.table');
    }
}