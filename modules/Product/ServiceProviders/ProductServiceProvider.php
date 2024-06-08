<?php

namespace Modules\Product\ServiceProviders;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{


    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__. '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__. '/../config.php', 'order');

        $this->app->register(RouteServiceProvider::class);
    }
}
