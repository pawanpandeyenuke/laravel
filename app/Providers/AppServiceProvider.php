<?php

namespace App\Providers;

use Auth, App\Country;
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
		// $usr = Auth::check();
  //       echo '<pre>';print_r($usr);die('Null');
        view()->share('countries', self::prepare(Country::all(['country_id', 'country_name'])));


    }

    /**
    * Prepare options array to share across views.
    *
    * @return array 
    */
    public function prepare( $data )
    {   

        // echo '<pre>';print_r(get_class($data));die;
        $preparedData = array();
        $preparedData[0] = 'Country';
        foreach( $data as $val ){

            $preparedData[$val->country_id] = $val->country_name;

        }

        return $preparedData;
        
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
