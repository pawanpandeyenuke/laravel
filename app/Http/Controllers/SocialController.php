<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use Hash, Session;
use App\User, DB;
use Socialite;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use \Exception;

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
			if( $userDbObj ) 
			{
				if($userDbObj->is_email_verified == 'N'){
					return 'verification';
				}
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
					$providerUser['password'] = ''; //Hash::make($tempEmail[0]);
					$raw_token = $providerUser['first_name'].date('Y-m-d H:i:s',time()).$providerUser['last_name'].$providerUser['email'];
	        		$access_token = Hash::make($raw_token);
					$providerUser['access_token'] = $access_token;
					$providerUser['is_email_verified'] = 'Y';
					$userDbObj = $user->create($providerUser);

					// Save default settings
			        DB::table('settings')->insert(['setting_title'=>'contact-request','setting_value'=>'all','user_id'=>$userDbObj->id]);
	        		DB::table('settings')->insert(['setting_title'=>'friend-request','setting_value'=>'all','user_id'=>$userDbObj->id]);

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

    	try {
    		if( $providerUser = \Socialite::driver( $provider )->user() ){
		        switch( $provider ){
					
					case 'facebook':
					$nameRaw = explode(' ', $providerUser->getName());
						$userData = array(
							'fb_id' => $providerUser->getId(),
							'nickname' => $providerUser->getNickname(),
							'first_name' => trim($nameRaw[0]),
							'last_name' => trim($nameRaw[1]),
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
							'first_name' => trim($nameRaw[0]),
							'last_name' => trim($nameRaw[1]),
							'email' => $providerUser->getEmail(),
							'avatar' => $providerUser->getAvatar(),
							'src' => 'twitter'
						);
						break;
						
					case 'google':

						$userData = array(
							'google_id' => $providerUser->getId(),
							'first_name' => trim($providerUser->user['name']['givenName']),
							'last_name' => trim($providerUser->user['name']['familyName']),
							'email' => $providerUser->getEmail(),
							'avatar' => $providerUser->getAvatar(),
							'src' => 'google'
						);
						break;
						
					case 'linkedin':

						$userData = array(
							'linked_id' => $providerUser->getId(),
							'nickname' => $providerUser->getNickname(),
							'first_name' => trim($providerUser->user['firstName']),
							'last_name' => trim($providerUser->user['lastName']),
							'email' => $providerUser->getEmail(),
							'avatar' => $providerUser->getAvatar(),
							'src' => 'linked'
						);
						break;
					
					default :
						
						$userData = array();
						break;			
				}
				
		    	$user = self::socialLogin( $userData );
		    	if( is_object($user) ) 
		    	{
		    		Auth::login($user);
		    		return redirect('home');
		    	} elseif($user == 'verification'){
		    		Session::put('success', 'Verification link has been sent to your registered email. Please check your inbox and verify email.<a href="#" title="Login" data-toggle="modal" data-target="#LoginPop">  Login</a>');
					return redirect('send-verification-link');
		    	}
		    	
		    	return redirect('register?first_name='.$userData['first_name'].'&last_name='.$userData['last_name'].'&src='.$userData['src']);
	    	} else {
	    		throw new Exception("Error Processing Request", 1);
	    	}
    	} catch( Exception $e){
    		Session::put('error', "Please try again, social login was canceled");
    		return redirect('register' );
    	}
    }
}