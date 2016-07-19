<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Hash, Session;
use App\User;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class SocialController extends Controller
{
	/**
	 *  @Commom Social Login Function
	 */
	public function socialLogin( $providerUser )
	{

//		echo '<pre>';print_r($providerUser);die;

		if( !empty( $providerUser ) ){
unset($providerUser['email']);
			if( isset( $providerUser['email']) ){
				$userDbObj = User::whereEmail($providerUser['email'])->first();
				if(!$userDbObj){	
					//register user
					$user = new User;
					$tempEmail = explode('@', $providerUser['email']);
					$providerUser['password'] = Hash::make($tempEmail[0]);
					$raw_token = $providerUser['first_name'].date('Y-m-d H:i:s',time()).$providerUser['last_name'].$providerUser['email'];
	        			$access_token = Hash::make($raw_token);
					$providerUser['access_token'] = $access_token;
					//print_r($user);die;
					$userDbObj = $user->create($providerUser);
				}
				return $userDbObj;
			}else{
				return false;
			}

		}
	}


	/**
	 *  @Redirect Function for different providers.
	 */
    public function redirect( $provider )
    {	
        return Socialite::driver( $provider )->redirect();   
    }


	/**
	 *  @Callback Function for different providers.
	 */
    public function callback( $provider )
    {		

        $providerUser = \Socialite::driver( $provider )->user();
        
        switch( $provider ){
			
			case 'facebook':
			$nameRaw = explode(' ', $providerUser->getName());
				$userData = array(
					'fb_id' => $providerUser->getId(),
					'nickname' => $providerUser->getNickname(),
					'first_name' =>$nameRaw[0],// $providerUser->user['first_name'],
					'last_name' =>$nameRaw[1],// $providerUser->user['last_name'],
					'email' => '', //$providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar()
				);
				break;
			
			case 'twitter':
				//echo '<pre>';print_r($providerUser->getEmail());die;
				//$email = $providerUser->getNickname().'@twitter.com';
				$nameRaw = explode(' ', $providerUser->getName());
				
				$userData = array(
					'twitter_id' => $providerUser->getId(),
					'nickname' => $providerUser->getNickname(),
					'first_name' => $nameRaw[0],
					'last_name' => $nameRaw[1],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar()
				);
				break;
				
			case 'google':

				$userData = array(
					'google_id' => $providerUser->getId(),
					'first_name' => $providerUser->user['name']['givenName'],
					'last_name' => $providerUser->user['name']['familyName'],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar()
				);
				break;
				
			case 'linkedin':

				$userData = array(
					'linked_id' => $providerUser->getId(),
					'nickname' => $providerUser->getNickname(),
					'first_name' => $providerUser->user['firstName'],
					'last_name' => $providerUser->user['lastName'],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar()
				);
				break;
			
			default :
				
				$userData = array();
				break;			
		}

//        $user = self::socialLogin( $userData );
  //      Auth::login($user);
$userData = (array)$userData;
foreach($userData as $key => $val){
Session::put($key, $val);
}
        return redirect('register');
    }

}
