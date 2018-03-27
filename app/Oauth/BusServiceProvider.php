<?php

namespace App\Oauth;

use Illuminate\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
	    $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
	    $socialite->extend(
		    'bus',
		    function ($app) use ($socialite) {
			    $config = $app['config']['services.bus'];
			    return $socialite->buildProvider(BusOauthProvider::class, $config);
		    }
	    );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
