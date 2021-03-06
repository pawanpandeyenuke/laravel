<?php

namespace App\Http\Controllers\Auth;
	
use Auth,Hash,URL;
use DB;
use App\Library\Converse;
use Socialite;
use App\User,App\Country,Mail,Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Request;

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
            'country' => 'required',
            'phone_no' => 'required|unique_with:users,country_code'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {
        $confirmation_code = str_random(30);
        $raw_token = $data['first_name'].date('Y-m-d H:i:s',time()).$data['last_name'].$data['email'];
        $access_token = Hash::make($raw_token);

        $data['country'] = Country::where('country_id',$data['country'])->value('country_name');

       /* if($data['country_code'] != 0 && $data['phone_no'] != null)
        {
            $min = countryMobileLength($data['country_code']);
            $len = strlen($data['phone_no']);
            if($len > $min[$data['country_code']]['max'] || $len < $min[$data['country_code']]['min'])
            { 
                $data['phone_no'] = "";
                $data['country_code'] = "";
            }
        }*/

        if(!(isset($data['gender'])))
            $data['gender'] = "";

        $userdata = User::create([
            'first_name' => trim($data['first_name']),
            'last_name' => trim($data['last_name']),
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'country' => $data['country'],
            'country_code' => str_replace('+', '', $data['country_code']),
            'phone_no' => $data['phone_no'],
            'gender' => $data['gender'],
            'confirmation_code' => $confirmation_code,
            'is_email_verified' => 'N',
            'access_token' => $access_token,
            'fb_id' => isset($data['fb_id']) ? $data['fb_id'] : null,
            'twitter_id' => isset($data['twitter_id']) ? $data['twitter_id'] : null,
            'google_id' => isset($data['google_id']) ? $data['google_id'] : null,
            'linked_id' => isset($data['linked_id']) ? $data['linked_id'] : null
        ]);
        
        $user = User::find($userdata->id);

        $xmppUserDetails = Converse::createUserXmppDetails($userdata);

        $useremail = $data['email'];
        $username = $data['first_name']." ".$data['last_name'];

        Converse::setNameVcard($user->xmpp_username, 'FN', $username);
        
        $emaildata = array(
            'confirmation_code' => $confirmation_code,
            'email' => $data['email'],
            'fullname' => $username,
        );

        Mail::send('emails.verify',$emaildata, function($message) use($useremail, $username){
            $message->from('contact@friendzsquare.com', 'FriendzSquare');
            $message->to($useremail,$username)->subject($username.'.. Please authenticate your email Address');
        });
       
        DB::table('settings')->insert(['setting_title'=>'contact-request','setting_value'=>'all','user_id'=>$userdata->id]);

        DB::table('settings')->insert(['setting_title'=>'friend-request','setting_value'=>'all','user_id'=>$userdata->id]);

        $converse = new Converse;
        $response = $converse->register($xmppUserDetails->xmpp_username, $xmppUserDetails->xmpp_password);
        
        Session::put('success', 'Verification link has been sent to your registered email. Please check your inbox and verify email.<a href="#" title="" data-toggle="modal" data-target="#LoginPop">  Login</a>');
        
        $this->redirectTo = 'send-verification-link';
        return $userdata;   
    }
}