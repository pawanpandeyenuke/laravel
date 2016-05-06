<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Requests;
use Request, Session, Validator, Input, Cookie;
use App\User, Auth;
class SearchController extends Controller
{
    
     public function searchFromUsers()
    {

        if(Request::isMethod('post')){
            $input = Request::all();
            $name = $input['searchfriends'];
            if($name == ""){
              return redirect('/');
            }
            if(Auth::Check())
            {
            $model1 = User::where('id','!=',Auth::User()->id)
                            ->where(function($query) use ($name){
                                $query->where('first_name','LIKE','%'. $name.'%');
                                 $query->orWhere('last_name','LIKE','%'. $name.'%');
                                })      
                            ->take(10)
                            ->orderBy('id','desc')
                            ->get()
                            ->toArray();
                

            $count = User::where('id','!=',Auth::User()->id)
                          ->where(function($query) use ($name){
                                $query->where('first_name','LIKE','%'. $name.'%');
                                $query->orWhere('last_name','LIKE','%'. $name.'%');
                                })      
                          ->take(10)
                            ->orderBy('id','desc')
                            ->get()
                            ->count();
                      $auth = 1;
             }
             else
             {
             	   $model1 = User::where('first_name','LIKE','%'. $name.'%')
								->orWhere('last_name','LIKE','%'. $name.'%')      
								->take(10)
								->orderBy('id','desc')
								->get()
								->toArray();
		            

            	$count = User::where('first_name','LIKE','%'. $name.'%')
							->orWhere('last_name','LIKE','%'. $name.'%')      
							->take(10)
							->orderBy('id','desc')
							->get()
							->count();

							$auth = 0;
             }
        return view('dashboard.allusers')
                ->with('model1',$model1)
                ->with('count',$count)
                ->with('keyword',$input['searchfriends'])
                ->with('auth',$auth);    
        
        }

        
    }
}
