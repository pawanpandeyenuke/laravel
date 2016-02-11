<?php

namespace App\Providers;

use App\Country;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		
		$countries = Country::all(['country_id', 'country_name']);
		//print_r();die;
		view()->share('countries', $countries);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
