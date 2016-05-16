<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Requests;
use Request, Session, Validator, Input, Cookie;
use App\User, Auth,Mail;
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

    public function contactUs()
    {
        $arguments = Request::all();
        $feedbackid = "feedback@friendzsquare.com";
        if($arguments['email'] == "")
            $arguments['email'] = "Anonymous User";

        self::suggestionMail($feedbackid,$arguments['message_text'],'Suggestion',$arguments['email']);

        //Session::put('success', 'Thank you for your valuable suggestion!');
        
        return 'success';
        // return redirect()->back()->with('success', 'Thank you for your valuable suggestion!');


    }

    public function suggestionMail($email = '', $message_text, $subject,$usermail) {
  
        $data = array(
            'message_text' => $message_text,
            'subject' => $subject,
            'usermail'=>$usermail
        );

        if($email != ''){
        Mail::send('emails.suggestion', $data, function($message) use($email, $subject) {
        $message->from($email, 'User Feedback');
        $message->to('adi490162@gmail.com')->subject($subject);
    });
        }
    }

    public function newPassword()
    {
        Auth::logout();
        return view('auth.passwords.newpassword');
    }


}
