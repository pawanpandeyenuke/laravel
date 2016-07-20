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
		if( !empty( $providerUser ) )
		{
			$social_id = $providerUser['src'].'_id';
			$social_id_value = $providerUser[$providerUser['src'].'_id'];
			$userDbObj = User::where([$social_id => $social_id_value])->first();
			if( $userDbObj ) {
				return $userDbObj;
			}
			elseif( isset( $providerUser['email']) && $providerUser['email'])
			{
				$userDbObj = User::whereEmail($providerUser['email'])->first();
				if(!$userDbObj)
				{
					// Register user
					$user = new User;
					$tempEmail = explode('@', $providerUser['email']);
					$providerUser['password'] = Hash::make($tempEmail[0]);
					$raw_token = $providerUser['first_name'].date('Y-m-d H:i:s',time()).$providerUser['last_name'].$providerUser['email'];
	        		$access_token = Hash::make($raw_token);
					$providerUser['access_token'] = $access_token;
					$userDbObj = $user->create($providerUser);
					return $userDbObj;
				}
				else 
				{
					if(!$userDbObj->$social_id)
					{
						$userDbObj->$social_id = $social_id_value;
						$userDbObj->save();
					}
					return $userDbObj;
				}
			} else {
				Session::put($providerUser['src'].'_id', $providerUser[$providerUser['src'].'_id']);
			}
		}
		return false;
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
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar(),
					'src' => 'fb'
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
					'avatar' => $providerUser->getAvatar(),
					'src' => 'twitter'
				);
				break;
				
			case 'google':

				$userData = array(
					'google_id' => $providerUser->getId(),
					'first_name' => $providerUser->user['name']['givenName'],
					'last_name' => $providerUser->user['name']['familyName'],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar(),
					'src' => 'google'
				);
				break;
				
			case 'linkedin':

				$userData = array(
					'linked_id' => $providerUser->getId(),
					'nickname' => $providerUser->getNickname(),
					'first_name' => $providerUser->user['firstName'],
					'last_name' => $providerUser->user['lastName'],
					'email' => $providerUser->getEmail(),
					'avatar' => $providerUser->getAvatar(),
					'src' => 'linked'
				);
				break;
			
			default :
				
				$userData = array();
				break;			
		}
		
        if( isset( $userData['email']) && $userData['email'])
		{
        	$user = self::socialLogin( $userData );
        	Auth::login($user);
        	return redirect('home');
    	}

    	return redirect('register?first_name='.$userData['first_name'].'&last_name='.$userData['last_name'].'&src='.$userData['src']);
    }
}