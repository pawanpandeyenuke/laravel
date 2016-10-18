<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Auth\Passwords\PasswordBroker;
use Password, Redirect, Validator;
// use App\Http\Controllers\Auth\Password;
use App\User, Session;

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


    // Reset password override 
    public function resetPassword()
    {
        $input = \Request::all();

        $pswdCheck = User::whereEmail($input['email'])->first();
        if( $pswdCheck && empty($pswdCheck->password) ){
            Session::put('error', $pswdCheck->email.' is already registered with us using social login. Please try social login.');
            return redirect()->back();
        }

        unset($input['_token']);
        $response = $this->passwords->sendResetLink($input, function($message) {
            $message->subject('Password Change Request');
        });
        
        switch ($response)
        {
            case PasswordBroker::RESET_LINK_SENT:
                Session::put('success', trans($response));
                break;
            
            case PasswordBroker::INVALID_USER:
                Session::put('error', trans($response));
                break;
        }
        
        return redirect()->back();
    }




    public function reset(Request $request)
    {

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $validator = Validator::make($credentials, [
                    'token' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|confirmed|min:6',
                ]);

        if( !$validator->fails() )
        {
            $broker = $this->getBroker();

            $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
                $user->forceFill([
                            'password' => bcrypt($password),
                            'remember_token' => Str::random(60),
                        ])->save();
            });

            return redirect('newpassword');

        }

    }


}