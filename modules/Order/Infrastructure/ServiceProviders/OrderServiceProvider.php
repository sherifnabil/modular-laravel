<?php

namespace Modules\Order\Infrastructure\ServiceProviders;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{


    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__. '/../database/migrations');
        $this->mergeConfigFrom(__DIR__. '/../config.php', 'order');

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->loadViewsFrom(__DIR__. '/../../Ui/Views', 'order');

        Blade::anonymousComponentPath(__DIR__. '/../../Ui/Views/components', 'order');
        Blade::componentNamespace('Modules\\Order\\Ui\\ViewComponents', 'order');
    }
}
