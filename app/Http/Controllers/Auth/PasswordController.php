<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Auth\Passwords\PasswordBroker;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    protected $redirectTo = 'newpassword';
    public function __construct(Guard $auth, PasswordBroker $passwords)
    {
        $this->auth = $auth;
        $this->passwords = $passwords;
        $this->middleware('guest');
    }
    
    // Send reset password link
    public function sendResetPasswordLink($email)
    {
        $response = $this->passwords->sendResetLink($email, function($message) {
            $message->subject('Password Change Request');
        });

        switch ($response)
        {
            case PasswordBroker::RESET_LINK_SENT:
                return true;
            
            case PasswordBroker::INVALID_USER:
                return trans($response);
        }
    }
}