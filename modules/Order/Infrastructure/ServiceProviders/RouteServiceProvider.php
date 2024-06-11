<?php
namespace Modules\Order\Infrastructure\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->routes(function() {

            Route::middleware('web')
            ->as('order::')
            ->group(__DIR__. '/../../routes.php');
        });

    }

}
