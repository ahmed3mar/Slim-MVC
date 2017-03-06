<?php

return [
    'displayErrorDetails' => true, // set to false in production
    'addContentLengthHeader' => false, // Allow the web server to send the content-length header

    'base_url'  => 'http://localhost:1254',

    'view' => [
        'templates' => __DIR__ . '/../../resources/views',
        'config'    => [
            'cache' => __DIR__ . '/../../storage/framework/cache/twig', # false or path to cache
            'debug' => true,
        ],
        'functions' => ['url','get','set','request','response', 'app']
    ],

    // Monolog settings
    'logger' => [
        'name' => 'slim-app',
        'path' => __DIR__ . '/../storage/framework/logs/app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],

    'db' => [
        'driver'    => 'mysql',
        'host'      => 'localhost',
        'database'  => 'doctors',
        'username'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => '',
    ]
];