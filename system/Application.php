<?php

namespace system;

use Dotenv\Dotenv;
use system\Controller\Controller;

class Application
{
    public function __construct() {

        $this->init_vendor_autoload();

        $this->loadEnv();

        spl_autoload_register([$this,'init_autoload_system']);

        session_start();
    }

    public function run(): void {

        $filters = new Filters();

        if($filters->path_is_update()) {
            $path = $filters->applyRedirectPath();
        } else {
            $path = PATH;
        }

        $controller = new Controller($path);

        if($controller->callablePagePath != 'json'){
            extract($controller->dataPage);

            @include_once $controller->callablePagePath;
        }

    }

    protected function loadEnv(): void {
        $dotenv = Dotenv::createImmutable(getcwd());
        $dotenv->load();

        if($_ENV['ENVIRONNEMENT'] === 'PROD') { // development
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
    }

    protected function init_vendor_autoload(): void {
        require_once('vendor/autoload.php');

    }

    protected function init_autoload_system($className) : void {
        $className = str_replace('\\',DIRECTORY_SEPARATOR,$className);

        $classPath = BASE_PATH.DIRECTORY_SEPARATOR.$className.'.php';

        if(is_file($classPath)) {

            require_once $classPath;

        }

    }

}