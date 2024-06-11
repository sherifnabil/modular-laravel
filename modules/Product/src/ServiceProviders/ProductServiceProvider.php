<?php

namespace Modules\Product\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Modules\Product\ServiceProviders\RouteServiceProvider;

class ProductServiceProvider extends ServiceProvider
{


    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__. '/../../database/migrations');
        $this->mergeConfigFrom(__DIR__. '/../../config/config.php', 'order');

        $this->app->register(RouteServiceProvider::class);
    }
}
