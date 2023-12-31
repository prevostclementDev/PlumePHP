<?php

require_once('vendor/autoload.php');

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if($_ENV['ENVIRONNEMENT'] === 'production') { // development
    error_reporting(0);
    define('DB_HOST',$_ENV['DB_PROD_HOST']);
    define('DB_USER',$_ENV['DB_PROD_USER']);
    define('DB_NAME',$_ENV['DB_PROD_NAME']);
    define('DB_PASS',$_ENV['DB_PROD_PASS']);
    define('BASE_PATH',$_ENV['BASE_PATH_PROD']);
    define('BASE_URI',$_ENV['BASE_URI_PROD']);
} else {
    define('DB_HOST',$_ENV['DB_DEV_HOST']);
    define('DB_USER',$_ENV['DB_DEV_USER']);
    define('DB_NAME',$_ENV['DB_DEV_NAME']);
    define('DB_PASS',$_ENV['DB_DEV_PASS']);
    define('BASE_PATH',$_ENV['BASE_PATH_DEV']);
    define('BASE_URI',$_ENV['BASE_URI_DEV']);
}

define("PATH", ($_SERVER['PATH_INFO'] ?? '/'));