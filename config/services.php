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
    		'redirect' => 'http://friendzsquare.com/callback/facebook',
	],
 
	'twitter' => [
            'client_id' => 'tu7f4c1WegVX2UzybYkHbKQnu',
            'client_secret' => 'pavx5Ies5YtKSFL675hsyBKPXdvTtILmd6jS3lSe8k6JJdsfTC',
            'redirect' => 'http://friendzsquare.com/callback/twitter',
	],
    
	'google' => [
            'client_id' => '497778402485-761fpbrmt0vucml85gk7be49c8fpmi7b.apps.googleusercontent.com',
            'client_secret' => 'edvSDQiJBSwgsgPSmems9RnA',
            'redirect' => 'http://friendzsquare.com/callback/google',
	],
    
	'linkedin' => [
    		'client_id' => '75kno0ahk9abe7',
    		'client_secret' => '4c3Cjv0urMvDVWqa',
    		'redirect' => 'http://friendzsquare.com/callback/linkedin', 
	],
    

];
