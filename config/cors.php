<?php

    return [

        'paths' => [
            'api/*',
            'login',
            'logout',
            'admin/*',
            'sanctum/csrf-cookie',
        ],

        'allowed_methods' => ['*'],

        'allowed_origins' => [
            '*'
        ],

        'allowed_headers' => ['*'],

        'exposed_headers' => [],

        'max_age' => 0,

        'supports_credentials' => true,
    ];
