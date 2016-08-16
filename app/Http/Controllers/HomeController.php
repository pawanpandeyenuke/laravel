<?php

namespace App\Http\Controllers;

use Validator,Request;
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
        if( isset($data['access_token']) && $data['access_token']=='rinku@xmpp' )
        {
            $user = User::where(['xmpp_username'=> $data['to']])->first();
            if( $user && $user->device_type == 'IPHONE')
            {
                $sender = User::where(['xmpp_username'=> $data['from']])->first();
                iphonePushNotification(array(
                    'notification_type' => 'text',
                    'message' => $sender->first_name.' '.$sender->last_name.' has sent a message',
                    'token' => $user->push_token
                ), array(
                    "isMediaMessage" => "0",
                    "type" => "text",
                    "text" => $data['body'],
                    "media" => null,
                    "senderid" => $sender->xmpp_username,
                    "first_name" => $sender->first_name,
                    "last_name" => $sender->last_name
                ));
            }
        }
    }
}