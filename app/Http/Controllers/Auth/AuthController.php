<?php

namespace App\Http\Controllers\Auth;
	
use Auth;
use DB;
use App\Library\Converse;
use Socialite;
use App\User,Mail,Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $confirmation_code = str_random(30);

        $userdata = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
	        'phone_no' => $data['phone_no'],
            'confirmation_code' => $confirmation_code,
            'is_email_verified' => 'N'
        ]);
        $xmpp_username = $userdata->first_name.$userdata->id;
        $xmpp_password = 'enuke'; //substr(md5($userdata->id),0,10);

        $user = User::find($userdata->id);
        $user->xmpp_username = strtolower($xmpp_username);
        $user->xmpp_password = $xmpp_password;
        $user->save();

        $useremail = $data['email'];
        $username = $data['first_name']." ".$data['last_name'];

         $emaildata = array(
            'confirmation_code' => $confirmation_code,
        );

        Mail::send('emails.verify',$emaildata, function($message) use($useremail, $username){
        $message->from('no-reply@friendzsquare.com', 'Verify Friendzsquare Account');
        $message->to($useremail,$username)->subject('Verify your email address');
        });
       
        DB::table('settings')->insert(['setting_title'=>'contact-request','setting_value'=>'All','user_id'=>$userdata->id]);


        DB::table('settings')->insert(['setting_title'=>'friend-request','setting_value'=>'All','user_id'=>$userdata->id]);
        $converse = new Converse;
        $response = $converse->register($xmpp_username, $xmpp_password);
        
        Session::put('success', 'Thanks for signing up! Please check your email to verify your account.');
        
        $vcard = $converse->setVcard($xmpp_username, $user->picture);
//        echo '<pre>';print_r($vcard);//die;
        return $userdata;

        
    }

    public function confirm($confirmation_code)
    {
        if( ! $confirmation_code)
        {
            throw new InvalidConfirmationCodeException;
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if ( ! $user)
        {
            throw new InvalidConfirmationCodeException;
        }

        $user->is_email_verified = 'Y';
        $user->confirmation_code = null;
        $user->save();

        Flash::message('You have successfully verified your account.');

        return Redirect::route('/');
    }

}
