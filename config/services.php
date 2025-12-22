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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'roundcube' => [
    'url' => env('ROUNDCUBE_URL', 'http://localhost:8082'),
    'secret_key' => env('ROUNDCUBE_SECRET_KEY', 'M3d#kF9@zT2qY8!pL5sX7vR$wN1cB4jG'),
    'db_connection' => env('ROUNDCUBE_DB_CONNECTION', 'roundcube'),
    'imap_host' => env('ROUNDCUBE_IMAP_HOST', 'localhost'),
    'imap_port' => env('ROUNDCUBE_IMAP_PORT', 143),
],

];
