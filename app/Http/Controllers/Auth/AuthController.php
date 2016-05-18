<?php

namespace App\Http\Controllers\Auth;
	
use Auth;
use DB;
use App\Library\Converse;
use Socialite;
use App\User;
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
    protected $redirectTo = '/dashboard';

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

        $userdata = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
	        'phone_no' => $data['phone_no'],
        ]);
        $xmpp_username = $userdata->first_name.$userdata->id;
        $xmpp_password = 'enuke'; //substr(md5($userdata->id),0,10);

        $user = User::find($userdata->id);
        $user->xmpp_username = strtolower($xmpp_username);
        $user->xmpp_password = $xmpp_password;
        $user->save();


        DB::table('settings')->insert(['setting_title'=>'contact-request','setting_value'=>'All','user_id'=>$userdata->id]);


        DB::table('settings')->insert(['setting_title'=>'friend-request','setting_value'=>'All','user_id'=>$userdata->id]);
        $converse = new Converse;
        $response = $converse->register($xmpp_username, $xmpp_password);
        

        $vcard = $converse->setVcard($xmpp_username, $user->picture);
        // echo '<pre>';print_r($vcard);die;
        return $userdata;
    }

}
