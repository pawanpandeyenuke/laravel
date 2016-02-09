<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Socialite;
use Laravel\Socialite\Contracts\Provider;

class SocialAuthController extends Controller
{
 
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();   
    }   

    public function callback()
    {
		
		$providerUser = \Socialite::driver('facebook')->user(); 
		print_r($providerUser);
		
    }

	//~ //Redirect url hits here..
	//~ public function redirect($provider)
	//~ {
		//~ print_r($provider);die;
		//~ return Socialite::driver($provider)->redirect();    
		//~ 
	//~ }
//~ 
	//~ //Callback url hits here..
	//~ public function callback(SocialAccountService $service, $provider)
	//~ {
		//~ 
		//~ $user = $service->createOrGetUser(Socialite::driver($provider));
//~ 
		//~ auth()->login($user);
//~ 
		//~ return redirect()->to('/home');
		//~ 
	//~ }
    //~ 
	//~ //Create or get the user..
    //~ public function createOrGetUser(Provider $provider)
    //~ {
//~ 
        //~ $providerUser = $provider->user();
        //~ $providerName = class_basename($provider); 
//~ 
        //~ $account = SocialAccount::whereProvider($providerName)
            //~ ->whereProviderUserId($providerUser->getId())
            //~ ->first();
//~ 
        //~ if ($account) {
            //~ return $account->user;
        //~ } else {
//~ 
            //~ $account = new SocialAccount([
                //~ 'provider_user_id' => $providerUser->getId(),
                //~ 'provider' => $providerName
            //~ ]);
//~ 
            //~ $user = User::whereEmail($providerUser->getEmail())->first();
//~ 
            //~ if (!$user) {
//~ 
                //~ $user = User::create([
                    //~ 'email' => $providerUser->getEmail(),
                    //~ 'name' => $providerUser->getName(),
                //~ ]);
            //~ }
//~ 
            //~ $account->user()->associate($user);
            //~ $account->save();
//~ 
            //~ return $user;
//~ 
        //~ }
//~ 
    //~ }
    
}
