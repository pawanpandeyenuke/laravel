<?php

namespace App\Http\Controllers;

use Validator,Request, Session;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/');
    }

    // Register new user
    public function postRegister()
    {
        if(Request::isMethod('post'))
        {
            $data = Request::all();
            $validator = Validator::make($data, [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
                'country' => 'required'
            ]);

            // Validator errors
            $errors = $validator->errors();

            // Custom check for mobile existence
            if( $data['country_code'] && $data['phone_no'] )
            {
                $exist = User::where(['country_code' => $data['country_code'], 'phone_no' => $data['phone_no']])->count();
                if( $exist ) {
                    $errors->add('mobile_unique', 'This mobile number has already been taken.');
                }
            }
            
            if( !empty($errors->getMessages()) ) {
                unset($data['_token']);
                return redirect('/')->withErrors($errors)->with($data);
            }
            
            // Register user
            $userData = app()->make('App\Http\Controllers\Auth\AuthController')->create($data);
            return redirect('send-verification-link');
        }

        return view('auth.register');
    }

    // Send notification to iphone device
    public function sendpushtoios()
    {
        $data = Request::all();

$token=base64_decode($data['access_token']);
        if( isset($data['access_token']) && $token=='rinku@xmpp' )
        {
             $to=explode('@',$data['to']);
            $user = User::where(['xmpp_username'=> $to[0]])->first();
            if( $user && $user->device_type == 'IPHONE')
            {
                  $from=explode('@',$data['from']);
                $sender = User::where(['xmpp_username'=> $from[0]])->first();
                iphonePushNotification(array(
                    'notification_type' => 'text',
                    'message' => $sender->first_name.' '.$sender->last_name.' has sent a message',
                    'token' => $user->push_token
                ), array(
                    "isMediaMessage" => "0",
                    "type" => "text",
                    "text" => base64_decode($data['body']),
                    "media" => null,
                    "senderid" => $sender->xmpp_username,
                    "first_name" => $sender->first_name,
                    "last_name" => $sender->last_name
                ));
            }
            else
{
return "Invalid User";
}
        }else
         {
                  return "Invalid Token";
}
    }

    // Unsubscribe
    public function unsubscribeForumNotifications()
    {
        $data = Request::all();
        if( !isset($data['token']) || !$data['token'] ){
            return redirect('/');
        }

        $user = User::where('access_token', urldecode($data['token']))->first();
        if( !$user ){
            return view('errors.404');
        }

        if( $user->subscribe == 0 && !isset($data['success'])) {
            Session::put('success', 'You are already unsubscribed.');
        } elseif( $user->subscribe == 0 && isset($data['success']) ) {
            Session::put('success', 'You are unsubscribed successfully.');
        }
        else
        {
            if( isset($data['action']) && $data['action'] == 'yes' )
            {
                $user->subscribe = 0;
                $user->save();       
                return redirect('forums/unsubscribe?token='.$data['token'].'&action=yes&success=1');
            }
        }

        return view('forums.unsubscribe');
    }
}
