<?php

namespace App\Providers;

use Auth, App\Country, App\Category;
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
        
        // echo '<pre>';print_r($grp);die;

        view()->share([
                'countries' => self::prepare(Country::all(['country_id', 'country_name'])),
                'parent_category' => Category::where('parent_id', '=', 0)->get()
            ]);

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
