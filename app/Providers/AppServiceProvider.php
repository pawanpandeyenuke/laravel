<?php

namespace App\Providers;


use Auth, App\Country, App\Category,App\JobArea, App\Forums;

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
        
/*        $forum = Forums::where('parent_id', 0)->pluck('id')->toArray();
        foreach ($forum as $value) {
            $forumdata = Forums::find($value);
            $forumdata->img_url = $value.'.png';
            $forumdata->save();
        }
        echo '<pre>';print_r($forumdata);die; */

        $educationLevel = array('High school','Certificate/diploma','Associate degree','3 or 4 year undergraduate program','Post graduate degree','Post graduate degree - MBA','Post graduate degree - Masters','Post graduate degree - JD','Post graduate degree - PHD/Doctrate','Professional','Other degree');

        $specialization = array('Accounting','Arts','Economics','Engineer','English','Finance','HR','IT','Marketing','Mathematics','Medicine','Operations','Others');

        $gradYear = array("1990","1991","1992","1993","1994","1995","1996","1997","1998","1999","2000","2001","2002","2003","2004","2005","2006","2007","2008","2009","2010","2011","2012","2013","2014","2015");

        view()->share([
                'countries' => self::prepare(Country::all(['country_id', 'country_name'])),
                'parent_category' => Category::where('parent_id', '=', 0)->orderBy('display_order')->get(),
                'educationLevel' => $educationLevel,
                'specialization' => $specialization,
                'gradYear' => $gradYear,
                'jobarea' => JobArea::lists('job_area','job_area_id')->toArray(),
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
        //$preparedData[0] = 'Country';
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
