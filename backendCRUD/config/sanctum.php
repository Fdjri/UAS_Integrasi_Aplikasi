<?php

use Laravel\Sanctum\Sanctum;

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Domains/hosts yang akan menerima cookie autentikasi stateful.
    |
    */

    'stateful' => explode(
        ',',
        env('SANCTUM_STATEFUL_DOMAINS', '127.0.0.1:8001,localhost:8001')
    ),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    */

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Token Prefix
    |--------------------------------------------------------------------------
    */

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    */

    'middleware' => [
        'authenticate_session'  => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies'       => Illuminate\Cookie\Middleware\EncryptCookies::class,
        'validate_csrf_token'   => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ],

];
