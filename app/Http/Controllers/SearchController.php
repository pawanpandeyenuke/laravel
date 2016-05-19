<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

use App\Http\Requests;
use Request, Session, Validator, Input, Cookie;
use App\User, Auth, Mail, App\Friend;
class SearchController extends Controller
{
    
     public function searchFromUsers()
    {
        if(Request::isMethod('post')){

            $input = Request::all();
            $name = $input['searchfriends'];

            if($name == "")
                return redirect('/');

            if(Auth::Check())

            {   
                $authUserId = Auth::User()->id;
                $auth = 1;
                $pregMatch = preg_match('/\s/',$name); 

                if($pregMatch){
                    $name = explode(' ', $name);
                    $fname = $name[0];
                    $lname = $name[1];
                    $result = self::searchUsersFromSite($auth, $fname, $lname, $authUserId);
                }else{
                    $result = self::searchUsersFromSite($auth, $name, '', $authUserId);
                }

                $model1 = $result->toArray(); 
                $count = $result->count();
                $auth = 1;

            }else{
                $auth = 0;
                $pregMatch = preg_match('/\s/',$name); 

                if($pregMatch){
                    $name = explode(' ', $name);
                    $fname = $name[0];
                    $lname = $name[1];
                    $result = self::searchUsersFromSite($auth, $fname, $lname);
                }else{
                    $result = self::searchUsersFromSite($auth, $name);
                }

                $model1 = $result->toArray(); 
                $count = $result->count();
                $auth = 0;
             }

        return view('dashboard.allusers')
                ->with('model1',$model1)
                ->with('count',$count)
                ->with('keyword',$input['searchfriends'])
                ->with('auth',$auth);    
        
        }
        
    }


    public function searchUsersFromSite($auth, $firstname, $lastname = '', $authUserId = ''){

        if($auth){
            // echo '<pre>';print_r($authUserId);die;
            if( !empty( $firstname ) && !empty( $lastname ) ) {
                return User::where('id', '!=', $authUserId)
                        ->whereNotIn('id', Friend::where('user_id', '=', $authUserId)
                                                ->where('status', '=', 'Accepted')
                                                ->pluck('friend_id')
                                                ->toArray() )
                        ->where(function($query) use ( $firstname, $lastname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $lastname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }elseif( !empty($firstname ) ) {
                return User::where('id', '!=', $authUserId)
                        ->whereNotIn('id', Friend::where('user_id', '=', $authUserId)
                                                ->where('status', '=', 'Accepted')
                                                ->pluck('friend_id')
                                                ->toArray() )
                        ->where(function($query) use ( $firstname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $firstname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }
        }else{
            if( !empty( $firstname ) && !empty( $lastname ) ) {
                return User::where(function($query) use ( $firstname, $lastname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $lastname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }elseif( !empty($firstname ) ) {
                return User::where(function($query) use ( $firstname ){
                            $query->where('first_name','LIKE','%'. $firstname.'%');
                            $query->orWhere('last_name','LIKE','%'. $firstname.'%');
                        })
                        ->orderBy('id','desc')
                        ->get();
            }
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


    public function termsConditions()
    {
        return view('terms-conditions');
    }
}
