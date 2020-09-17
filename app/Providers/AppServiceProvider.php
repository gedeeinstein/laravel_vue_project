<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register(){
        require_once app_path() . '/Helpers/DatatablesHelper.php';
        require_once app_path() . '/Helpers/ImageHelper.php';
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot(){
        Schema::defaultStringLength(191);
        
        // support SSL. auto change URL variably.
        if (request()->isSecure()) {
            \URL::forceScheme('https');
        }
        // Blade::component('backend._components.breadcrumbs', 'breadcrumbs');
    }
}
