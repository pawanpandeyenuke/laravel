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
		if( !empty( $providerUser ) ){			
			if( isset( $providerUser['email']) ){				
				$userDbObj = User::whereEmail($providerUser['email'])->first();
				if(!$userDbObj){	
					//register user
					$user = new User;
					$tempEmail = explode('@', $providerUser['email']);
					$providerUser['password'] = Hash::make($tempEmail[0]);
					$userDbObj = $user->create($providerUser);

					$tempEmail = explode('@', $providerUser['email']);
					$tempId = ( isset( $userDbObj->id ) && $userDbObj->id != "" ) ? $userDbObj->id : $userDbObj->user_id;

					// Storing xmpp username and password.				
					$user = User::find($userDbObj->id);
					$user->xmpp_username = $tempEmail[0].'_'.$tempId;
					$user->xmpp_password = md5($tempEmail[0]);
					$user->save();
					
				}
				return $userDbObj;
			}
		}			
	}


	/**
	 *  @Facebook Redirect Function
	 */
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();   
    }

	/**
	 *  @Facebook Callback Function
	 */
    public function callback()
    {		
        $providerUser = \Socialite::driver('facebook')->user();
		$userData = array(
			'fb_id' => $providerUser->getId(),
			'nickname' => $providerUser->getNickname(),
			'first_name' => $providerUser->user['first_name'],
			'last_name' => $providerUser->user['last_name'],
			'email' => $providerUser->getEmail(),
			'avatar' => $providerUser->getAvatar()
		);
        
        $user= self::socialLogin( $userData );
        Auth::login($user);
        return redirect('home');
    }


	/**
	 *  @Twitter Redirect Function
	 */
	public function redirecttwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }


	/**
	 *  @Twitter Callback Function
	 */
    public function callbacktwitter()
    {		
		
		$email = 'demo.user@twitter.com';
        $providerUser = \Socialite::driver('twitter')->user();
		$nameRaw = explode(' ', $providerUser->getName());
		
		$userData = array(
			'twitter_id' => $providerUser->getId(),
			'nickname' => $providerUser->getNickname(),
			'first_name' => $nameRaw[0],
			'last_name' => $nameRaw[1],
			'email' => $email, //$providerUser->getEmail(),
			'avatar' => $providerUser->getAvatar()
		);

        $user = self::socialLogin( $userData );
        Auth::login($user);
        return redirect('home');
    }


	/**
	 *  @Google Redirect Function
	 */
	public function redirectgoogle()
    {
        return Socialite::driver('google')->redirect();
    }


	/**
	 *  @Google Callback Function
	 */
    public function callbackgoogle()
    {		
		
		//~ $email = 'demo.user@twitter.com';
        $providerUser = \Socialite::driver('google')->user();

		$userData = array(
			'google_id' => $providerUser->getId(),
			'nickname' => $providerUser->getNickname(),
			'first_name' => $providerUser->user['name']['givenName'],
			'last_name' => $providerUser->user['name']['familyName'],
			'email' => $providerUser->getEmail(),
			'avatar' => $providerUser->getAvatar()
		);

        $user = self::socialLogin( $userData );
        Auth::login($user);
        return redirect('home');
    }


	/**
	 *  @Linkedin Redirect Function
	 */
	public function redirectlinkedin()
    {
        return Socialite::driver('linkedin')->redirect();
    }


	/**
	 *  @Linkedin Callback Function
	 */
    public function callbacklinkedin()
    {		
		 
        $providerUser = \Socialite::driver('linkedin')->user();
        
		//~ echo '<pre>';print_r($providerUser);die;
		$userData = array(
			'linked_id' => $providerUser->getId(),
			'nickname' => $providerUser->getNickname(),
			'first_name' => $providerUser->user['firstName'],
			'last_name' => $providerUser->user['lastName'],
			'email' => $providerUser->getEmail(),
			'avatar' => $providerUser->getAvatar()
		);

        $user = self::socialLogin( $userData );
        Auth::login($user);
        return redirect('home');
    }
 
}
