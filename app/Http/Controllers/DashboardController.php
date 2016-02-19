<?php

namespace App\Http\Controllers;

use Auth, App\Feed;
use Request, Session, Validator, Input, Cookie;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index()
	{
/*		return view('dashboard.dashboard');
		return 'hello';*/
	}

	public function dashboard()
	{
        try{

            // print_r();die;
            if(Request::isMethod('post'))
            {
                $input = Request::all();
                if($input)
                {
                    $feeds = new Feed;
                    $feeds->message = $input['message'];
                    $feeds->image = isset($input['image']) ? $input['image'] : '';
                    $feeds->user_by = Auth::User()->id;
                    // print_r($feeds->user_by);die;
                    $feeds->save();
                }
                // echo '<pre>';print_r($input);die;
            }
        }catch( Exception $e){
            $this->error = $e->getMessage();
        }

		return view('dashboard.dashboard');
	}

}
