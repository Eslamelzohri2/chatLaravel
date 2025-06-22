<?php
return [

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'broadcasting/auth', // ✅ مهم
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173', // ✅ رابط تطبيق React
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // ✅ ضروري لـ Authorization Bearer token

];
