<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Mail;
use Hash;
use App\User;
use Socialite;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SocialAuthController extends Controller
{
	
	/**
	 *  @Commom Social Login Function
	 */
	public function socialLogin( $providerUser )
	{		
		
		 // echo '<pre>';print_r($providerUser);die;
		
		if( !empty( $providerUser ) ){		
			if( isset( $providerUser['email']) ){
				$userDbObj = User::whereEmail($providerUser['email'])->first();
				if(!$userDbObj){	
					//register user
					$user = new User;
					$tempEmail = explode('@', $providerUser['email']);
					$providerUser['password'] = Hash::make($tempEmail[0]);
					//print_r($providerUser);die;
					$userDbObj = $user->create($providerUser);

					$tempEmail = explode('@', $providerUser['email']);
					$tempId = ( isset( $userDbObj->id ) && $userDbObj->id != "" ) ? $userDbObj->id : $userDbObj->user_id;

				$raw_token = $providerUser['first_name'].date('Y-m-d H:i:s',time()).$providerUser['last_name'].$providerUser['email'];
        		$access_token = Hash::make($raw_token);
					// Storing xmpp username and password.				
					$user = User::find($userDbObj->id);
					$user->xmpp_username = $tempEmail[0].'_'.$tempId;
					$user->xmpp_password = md5($tempEmail[0]);
					$user->access_token = $access_token;
					$user->save();
					
				}
				return $userDbObj;
			}
		}			
	}


	/**
	 *  @Redirect Function for different providers.
	 */
    public function redirect( $provider )
    {	
    	 echo '<pre>';print_r($provider);die;
        return Socialite::driver( $provider )->redirect();   
    }


	/**
	 *  @Callback Function for different providers.
	 */
    public function callback( $provider )
    {		
 
        $providerUser = \Socialite::driver( $provider )->user();
        // echo '<pre>';print_r($providerUser);die;
        switch( $provider ){
			
			case 'facebook': 
				$raw_token = $providerUser->user['first_name'].date('Y-m-d H:i:s',time()).$providerUser->user['last_name'].$providerUser->getEmail();
        		$access_token = Hash::make($raw_token);
				$userData = array(
					'fb_id' => $providerUser->getId(),
					'nickname' => $providerUser->getNickname(),
					'first_name' =>$providerUser->user['first_name'],
					'last_name' => $providerUser->user['last_name'],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar(),
					'access_token'=>$access_token
				);
				break;
			
			case 'twitter':
			
				$email = 'demo.user@twitter.com';
				$nameRaw = explode(' ', $providerUser->getName());
				$raw_token = $nameRaw[0].date('Y-m-d H:i:s',time()).$nameRaw[1].$email;
        		$access_token = Hash::make($raw_token);
				$userData = array(
					'twitter_id' => $providerUser->getId(),
					'nickname' => $providerUser->getNickname(),
					'first_name' => $nameRaw[0],
					'last_name' => $nameRaw[1],
					'email' => $email, //$providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar(),
					'access_token'=>$access_token
				);
				break;
				
			case 'google':
				$raw_token = $providerUser->user['name']['givenName'].date('Y-m-d H:i:s',time()).$providerUser->user['name']['familyName'].$providerUser->getEmail();
        		$access_token = Hash::make($raw_token);
				$userData = array(
					'google_id' => $providerUser->getId(),
					'first_name' => $providerUser->user['name']['givenName'],
					'last_name' => $providerUser->user['name']['familyName'],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar(),
					'access_token'=>$access_token
				);
				break;
				
			case 'linkedin':
				$raw_token = $providerUser->user['firstName'].date('Y-m-d H:i:s',time()).$providerUser->user['lastName'].$providerUser->getEmail();
        		$access_token = Hash::make($raw_token);
				$userData = array(
					'linked_id' => $providerUser->getId(),
					'nickname' => $providerUser->getNickname(),
					'first_name' => $providerUser->user['firstName'],
					'last_name' => $providerUser->user['lastName'],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar(),
					'access_token'=>$access_token
				);
				break;
			
			default :
				
				$userData = array();
				break;			
		}

        $user = self::socialLogin( $userData );
        Auth::login($user);
        return redirect('home');
    }


}

