<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN', null),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID', null),
        'secret' => env('AWS_SECRET_ACCESS_KEY', null),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY', null),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN', null),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL', null),
        ],
    ],'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID', null),
    'client_secret' => env('GOOGLE_CLIENT_SECRET', null),
    'redirect' => env('GOOGLE_REDIRECT_URI', null),
],
'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID', null),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET', null),
    'redirect' => env('FACEBOOK_REDIRECT_URI', null),
],

];
