<?php

return [
    'useAuthorization' => false,
    'permissions' => require __DIR__ . '/permissions.example.php',
    'skipAuthorization' => [
        \App\Services\ApiDocumentation::class => [
            'generate'
        ],
    ],
];
