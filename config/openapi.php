<?php

return [
    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'WinCase CRM v4.0 API',
                'version' => '4.0.0',
                'description' => 'Immigration Bureau CRM — Complete REST API',
            ],
            'routes' => [
                'api' => 'api/documentation',
                'docs' => 'docs',
            ],
        ],
    ],

    'defaults' => [
        'routes' => [
            'middleware' => ['web'],
        ],
    ],
];
