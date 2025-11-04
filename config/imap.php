<?php
// config/imap.php

return [
    'default' => env('IMAP_DEFAULT_ACCOUNT', 'default'),
    
    'accounts' => [
        'default' => [
            'host'          => env('IMAP_HOST', 'localhost'),
            'port'          => env('IMAP_PORT', 993),
            'encryption'    => env('IMAP_ENCRYPTION', 'ssl'),
            'validate_cert' => true,
            'username'      => env('IMAP_USERNAME'),
            'password'      => env('IMAP_PASSWORD'),
            'protocol'      => env('IMAP_PROTOCOL', 'imap'),
            'timeout'       => 30,
        ],
    ],

    'options' => [
        'delimiter' => '/',
        'fetch' => \Webklex\IMAP\Support\MessageCollection::class,
        'fetch_order' => 'desc',
        'fetch_body' => true,
        'fetch_attachment' => false, // Désactivé pour les tests
        'fetch_flags' => true,
        'message_key' => 'list',
        'uid_cache' => false, // Désactivé pour éviter les conflits
        'debug' => env('IMAP_DEBUG', false),
    ],
];