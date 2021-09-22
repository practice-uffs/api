<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram API url
    |--------------------------------------------------------------------------
    |
    */
    'token_ttl_seconds' => env('PRACTICE_API_TOKEN_TTL_SECONDS', 120),

    /*
    |--------------------------------------------------------------------------
    | Lista de ids das aplicações
    |--------------------------------------------------------------------------
    |
    */
    'app_id' => [
        'mural' => env('PRACTICE_API_MURAL_APP_ID', 1),
        'forms' => env('PRACTICE_API_FORMS_APP_ID', 2),
        'maker' => env('PRACTICE_API_MAKER_APP_ID', 3),
    ]
];