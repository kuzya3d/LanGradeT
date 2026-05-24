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

    'gigachat' => [
        'authorization_key' => env('GIGACHAT_AUTHORIZATION_KEY'),
        'oauth_url' => env('GIGACHAT_OAUTH_URL', 'https://ngw.devices.sberbank.ru:9443'),
        'api_url' => env('GIGACHAT_API_URL', 'https://gigachat.devices.sberbank.ru'),
        'scope' => env('GIGACHAT_SCOPE', 'GIGACHAT_API_PERS'),
        'model' => env('GIGACHAT_MODEL', 'GigaChat'),
        'timeout' => env('GIGACHAT_TIMEOUT', 12),
        'connect_timeout' => env('GIGACHAT_CONNECT_TIMEOUT', 5),
        'verify_ssl' => filter_var(env('GIGACHAT_VERIFY_SSL', true), FILTER_VALIDATE_BOOLEAN),
        'proxy' => env('GIGACHAT_PROXY'),
    ],

    'g2p' => [
        'python' => env('G2P_PYTHON', 'python'),
    ],

];
