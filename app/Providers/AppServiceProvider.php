<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
	  \URL::forceScheme('http');
		// $this->app['request']->server->set('HTTPS','on');
	   Paginator::useBootstrapFive();
	   Paginator::useBootstrapFour();
	}
}
