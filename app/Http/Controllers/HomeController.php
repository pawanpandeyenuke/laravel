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
                return redirect('/')->withErrors($errors);
            }

            // Register user
            $userData = app()->make('App\Http\Controllers\Auth\AuthController')->create($data);
            return redirect('send-verification-link');
        }

        return view('auth.register');
    }
}
