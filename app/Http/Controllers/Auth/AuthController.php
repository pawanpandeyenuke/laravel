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
            'country' => 'required'
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
        $raw_token = $data['first_name'].date('Y-m-d H:i:s',time()).$data['last_name'].$data['email'];
        $access_token = Hash::make($raw_token);

        $data['country'] = Country::where('country_id',$data['country'])->value('country_name');

        if($data['country_code'] != 0 && $data['phone_no'] != null)
        {
            // echo 'asdad';die;
            $min = countryMobileLength($data['country_code']);
            $len = strlen($data['phone_no']);
            if($len > $min[$data['country_code']]['max'] || $len < $min[$data['country_code']]['min'])
            { 
                $data['phone_no'] = "";
                $data['country_code'] = "";
            }

        }

        if(!(isset($data['gender'])))
            $data['gender'] = "";

        $userdata = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'country' => $data['country'],
            'country_code' => str_replace('+', '', $data['country_code']),
            'phone_no' => $data['phone_no'],
            'gender' => $data['gender'],
            'confirmation_code' => $confirmation_code,
            'is_email_verified' => 'N',
            'access_token' => $access_token,
            'picture' => '/images/user-thumb.jpg'
        ]);
        
        $user = User::find($userdata->id);

        $xmppUserDetails = Converse::createUserXmppDetails($userdata);

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
        $response = $converse->register($xmppUserDetails->xmpp_username, $xmppUserDetails->xmpp_password);
        
        Session::put('success', 'Thanks for signing up! Please check your email to verify your account.');
    
        $this->redirectTo = 'send-verification-link';
        return $userdata;

        
    }

}
