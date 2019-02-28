<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

	//socialite
    'github' => [
	    'client_id' => env('GITHUB_CLIENT_ID'),         // Your GitHub Client ID
	    'client_secret' => env('GITHUB_CLIENT_SECRET'), // Your GitHub Client Secret
	    'redirect' => '/login/github/callback',
    ],
    'facebook' => [
	    'client_id' => env('FACEBOOK_CLIENT_ID'),         // Your facebook Client ID
	    'client_secret' => env('FACEBOOK_CLIENT_SECRET'), // Your facebook Client Secret
	    'redirect' => '/login/facebook/callback',
    ],
    'google' => [
	    'client_id' => env('GOOGLE_CLIENT_ID'),         
	    'client_secret' => env('GOOGLE_CLIENT_SECRET'), 
	    'redirect' => '/login/google/callback',
    ],

    'paypal' => [
        'id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'url' => [
            'executeAgreement' => [
                'success' => 'http://localhost:8000/execute-agreement/true',
                'failure' => 'http://localhost:8000/execute-agreement/false'
            ]
        ],
        'settings' => array(
            'mode' => env('PAYPAL_MODE','sandbox'),
            'http.ConnectionTimeOut' => 30000,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path() . '\logs\paypal.log',
            'log.LogLevel' => 'ERROR'
        ),
    ]
];
