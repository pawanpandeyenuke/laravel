<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => 'postmaster@sandboxf37e9b8f131240aeacf536512adba807.mailgun.org', //env('MAILGUN_DOMAIN'),
        'secret' => '10e923037f67f27a90d543b29cd9324a', //env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

	'facebook' => [
    		'client_id' => '147417315641264',
    		'client_secret' => '796d800b177429195b9994152301c82f',
    		'redirect' => 'http://www.friendzsquare.com/callback/facebook',
	],
 
	'twitter' => [
            'client_id' => 'REG6vRMOzfB1oauv1gsy5Y7Ln',
            'client_secret' => 'eOTpewvb0uBuBMAG8p4jzlLcfcRiYkJrKW5nWUOrGdWk67dZSy',
            'redirect' => 'http://www.friendzsquare.com/callback/twitter',
	        'scopes' => 'email',
	],
    
	'google' => [
            'client_id' => '497778402485-761fpbrmt0vucml85gk7be49c8fpmi7b.apps.googleusercontent.com',
            'client_secret' => 'edvSDQiJBSwgsgPSmems9RnA',
            'redirect' => 'http://www.friendzsquare.com/callback/google',
	],
    
	'linkedin' => [
    		'client_id' => '7560t23vhfay3w',
    		'client_secret' => 'AlhC8zayAwplo3Ks',
    		'redirect' => 'http://www.friendzsquare.com/callback/linkedin', 

	],
    

];
