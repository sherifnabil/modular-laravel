<?php

namespace Modules\Shipment\ServiceProviders;

use Illuminate\Support\ServiceProvider;
use Modules\Shipment\ServiceProviders\RouteServiceProvider;

class ShipmentServiceProvider extends ServiceProvider
{


    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__. '/../Database/Migrations');
        $this->mergeConfigFrom(__DIR__. '/../config.php', 'shipment');

        $this->app->register(RouteServiceProvider::class);
    }
}
