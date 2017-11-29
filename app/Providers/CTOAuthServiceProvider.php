<?php

namespace App\Providers;

use App\Providers\CTOUserServiceProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class CTOAuthServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {
		
    	Auth::provider('cto', function($app, array $config) {
        	return new CTOUserServiceProvider();
    	});
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register() {}
}