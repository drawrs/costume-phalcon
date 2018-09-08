<?php

return new \Phalcon\Config([
    'database' => [
        'host'     => env('DATABASE_HOST', 'secret'),
        'username' => env('DATABASE_USERNAME', 'secret'),
        'password' => env('DATABASE_PASSWORD', 'secret'),
        'dbname'   => env('DATABASE_NAME', 'secret'),
        'charset'  => env('DATABASE_CHARSET', 'utf8'),
    ],
    'application' => [
        'appDir'          => BASE_DIR . 'app/',
        'controllersDir'  => BASE_DIR . 'app/controllers/',
        'modelsDir'       => BASE_DIR . 'models/',
        'migrationsDir'   => BASE_DIR . 'migrations/',
        'viewsDir'        => BASE_DIR . 'app/views/',
        'cacheViewDir'    => BASE_DIR . 'storage/cache/views/',
        'logsDir'         => BASE_DIR . 'storage/logs/',
        'baseUri'         => env('URI_STATIC'),
    ]
]);
