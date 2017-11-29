<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class HelpersServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot() {}

	/**
	 * Register the application services.
	 * Require the file helpers
	 *
	 * @return void
	 */
	public function register() {
		require_once app_path().'/Helpers/auth.php';
		require_once app_path().'/Helpers/date.php';
        require_once app_path().'/Helpers/options.php';
        require_once app_path().'/Helpers/data.php';
	}
}