<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // Path mana saja yang boleh diakses dari frontend-mu
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Metode HTTP apa saja yang diizinkan
    'allowed_methods' => ['*'],

    // Domain/port frontend-mu
    'allowed_origins' => [
        'http://127.0.0.1:8001',
        'http://localhost:8001',
    ],

    // Header apa saja yang diizinkan
    'allowed_headers' => ['*'],

    // Header apa saja yang boleh di-expose ke browser
    'exposed_headers' => [],

    // Durasi preflight cache (dalam detik)
    'max_age' => 0,

    // Harus `true` agar cookie/credential dikirim
    'supports_credentials' => true,

];
