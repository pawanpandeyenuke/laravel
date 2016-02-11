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
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
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
		'redirect' => 'http://development.laravel.com/callback',
	],
 
	'twitter' => [
		'client_id' => 'yjJPO9ogaQBbQl127IBJS5bCV',
		'client_secret' => 'RMkAeOC47JWFS9d7zSDBCXgdN1InVEiTYdHCrdjsnmlYock8aX',
		'redirect' => 'http://development.laravel.com/callbacktwitter',
	],
    
	'google' => [
		'client_id' => '875545827153-ogtrtj4m0610tr7qc6ujrsbs98mq6fln.apps.googleusercontent.com',
		'client_secret' => 'QbW-lrmDMk8ZeVK0eJV9XB2v',
		'redirect' => 'http://development.laravel.com/callbackgoogle',
	],
    
	'linkedin' => [
		'client_id' => '75kno0ahk9abe7',
		'client_secret' => '4c3Cjv0urMvDVWqa',
		'redirect' => 'http://development.laravel.com/callbacklinkedin',
	],
    

];
