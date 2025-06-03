<?php

return [
    // Gunakan server key sandbox atau production sesuai env
    'server_key' => env('MIDTRANS_SERVER_KEY', ''),

    // Gunakan client key sandbox atau production sesuai env
    'client_key' => env('MIDTRANS_CLIENT_KEY', ''),

    // Jika true, akan pakai environment production, kalau false pakai sandbox
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Sanitasi data agar aman dari input berbahaya
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),

    // Aktifkan 3DS untuk kartu kredit
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];
