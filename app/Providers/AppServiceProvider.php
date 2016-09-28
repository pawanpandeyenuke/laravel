<?php

namespace App\Providers;


use Auth, App\Country, App\Category,App\JobArea, App\Forums, App\User;
use Hash, Session;
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

        $educationLevel = array(
                'High school',
                'certifciate/diploma',
                'Associate degre',
                '3 or 4 year undergraduate program',
                'Post graduate degree',
                'Post graduate degree - MBA',
                'Post graduate degree - Masters',
                'Post graduate degree - JD',
                'Post graduate degree - PHD/Doctrate',
                'Medical Doctor (M.D.)',
                'Doctor of Dental Surgery (D.D.S.)',
                'Doctor of Pharmacy (Pharm.D.)',
                'CA',
                'CFA',
                'ACCA',
                'CPA',
                'FRM',
                'IAS',
                'Other professional degree',
                'Others'
                );


        $specialization = array('Accounting ','Agriculture & Forestry','Anthropology','Archaeology','Architecture','Art & Design','Biological Sciences','Business & Management','Chemistry','CiviI & Structural Engineering','Communication & Media Studies','Computer Science','Dentistry','Development Studies','Earth & Marine Sciences','Economics & Econometrics','Education','Engineering - Aeronautical','Engineering - Chemical','Engineering – Civil and Structural','Engineering – Computer science','Engineering – Electrical & Electronic','Engineering – Manufacturing','Engineering – Mechanical','Engineering – Mining and Mineral','Engineering –Graphics','English Language & Literature','Environmental Sciences','Film Studies','Finance','Geography','Health and Nutrition','History','Law','Life Sciences & Medicine','Linguistics','Materials Sciences','Mathematics','Medicine','Modern Languages','Music','Natural Sciences','Nursing','Operational research','Performing Arts','Pharmacy & Pharmacology','Philosophy','Physics & Astronomy','Politics and International Studies','Psychology','Social Policy and Administration','Social Sciences','Sociology','Statistics','Veterinary Science','Others');

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
