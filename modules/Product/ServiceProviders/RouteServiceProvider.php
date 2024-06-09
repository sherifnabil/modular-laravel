<?php
namespace Modules\Product\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->routes(function() {

            Route::middleware('web')
            ->as('product')
            ->group(__DIR__. '/../routes.php');
        });

    }

}
