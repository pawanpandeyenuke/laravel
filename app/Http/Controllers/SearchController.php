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
            $keyword = $input['searchfriends'];

            if($keyword == "")
                return redirect('/');

            $authUserId = isset(Auth::User()->id) ? Auth::User()->id : '';

            $model = new User;

            // Search for the following people.
            if(trim($keyword) != ''){

                $model = $model->where( function( $query ) use ( $input, $keyword ) {
                    $expVal = explode(' ', $keyword);
                    foreach( $expVal as $key => $value ) {                          
                        $query->orWhere( 'last_name', 'LIKE', '%'. $value.'%' )
                            ->orWhere( 'first_name', 'LIKE', '%'. $value.'%' );  
                    }
                });

            }

            if( $authUserId != '' ){
                
                // User cannot search himself.
                $model = $model->where('id', '!=', $authUserId);

                // Search for user's who are not friends with me.
                $model = $model->whereNotIn('id', Friend::where('user_id', '=', $authUserId)
                                ->where('status', '=', 'Accepted')
                                ->pluck('friend_id')
                                ->toArray() );

            }

            // Gather all the results from the queries and paginate it.
            $result = $model->orderBy('id','desc')->get();   

            $model1 = $result->toArray(); 
            $count = $result->count();
            $auth = ($authUserId != '') ? 1 : 0;

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


    public function termsConditions()
    {
        return view('terms-conditions');
    }
}
