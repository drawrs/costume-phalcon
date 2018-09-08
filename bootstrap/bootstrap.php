<?php

use Dotenv\Dotenv;
use Phalcon\Loader;

define( 'BASE_DIR', dirname( __DIR__ ) . DIRECTORY_SEPARATOR );

// Include Composer autoloader
include BASE_DIR . 'vendor/autoload.php';

// Load environment variables
$dotenv = new Dotenv( realpath( BASE_DIR ) );
$dotenv->load();

// Creates the autoloader
$loader = new Loader();

// Register some namespaces
$loader->registerNamespaces(
    [
       "Qodr"              => BASE_DIR . 'app/library/',
       "Qodr\\Model"       => BASE_DIR . 'models/',
       "Qodr\\Controllers" => BASE_DIR . 'app/controllers/'
    ]
);

// Register some files
$loader->registerFiles(
    [
        BASE_DIR . "bootstrap/functions.php"
    ]
);

// Register autoloader
$loader->register();

// error exception
error_app_debug();