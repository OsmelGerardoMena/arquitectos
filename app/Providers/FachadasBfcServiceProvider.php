<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App;

class FachadasBfcServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        App::bind('bfc',function(){
            return new App\Fachadas\BfcFachada;
        });
    }
}
