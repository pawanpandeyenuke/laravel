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
        // Localhost -->
    		// 'client_id' => '147417315641264',
    		// 'client_secret' => '796d800b177429195b9994152301c82f',
    		// 'redirect' => url('/').'/callback/facebook',

        // Server -->
            'client_id' => '',
            'client_secret' => '',
            'redirect' => url('/').'/callback/facebook',
	],
 
	'twitter' => [
        // Localhost -->
    		// 'client_id' => 'yjJPO9ogaQBbQl127IBJS5bCV',
    		// 'client_secret' => 'RMkAeOC47JWFS9d7zSDBCXgdN1InVEiTYdHCrdjsnmlYock8aX',
    		// 'redirect' => url('/').'/callback/twitter',

        // Server -->
            'client_id' => '',
            'client_secret' => '',
            'redirect' => url('/').'/callback/twitter',
	],
    
	'google' => [
        // Localhost -->
    		// 'client_id' => '875545827153-ogtrtj4m0610tr7qc6ujrsbs98mq6fln.apps.googleusercontent.com',
    		// 'client_secret' => 'QbW-lrmDMk8ZeVK0eJV9XB2v',
    		// 'redirect' => url('/').'/callback/google',

        // Server -->
            'client_id' => '962290042733-3cjdj7f3jkojqnv6kct7dm1jg03nekoc.apps.googleusercontent.com',
            'client_secret' => 'cVXRNjz2DDb0eFcZB26JJS4_',
            'redirect' => url('/').'/callback/google',
	],
    
	'linkedin' => [
        // Localhost -->
    		// 'client_id' => '75kno0ahk9abe7',
    		// 'client_secret' => '4c3Cjv0urMvDVWqa',
    		// 'redirect' => url('/').'/callback/linkedin',

        // Server -->
            'client_id' => '',
            'client_secret' => '',
            'redirect' => url('/').'/callback/linkedin',
	],
    

];
