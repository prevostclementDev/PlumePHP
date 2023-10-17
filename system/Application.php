<?php

namespace system;

use Dotenv\Dotenv;
use system\Controller\Controller;
use system\Services\Helper;

/**
 * PlumePHP Application Class Documentation
 *
 * This class serves as the entry point for the PlumePHP PHP framework.
 * It is responsible for initializing the application, managing routes, and executing controllers.
 */

class Application
{
    /**
     * Constructor for the Application class
     *
     * Initializes the application, loads environment variables, configures the custom autoloader,
     * and starts the PHP session.
     */
    public function __construct() {
        // Initialize the application's autoloader
        $this->init_vendor_autoload();

        // Load environment variables
        $this->loadEnv();

        // Register the custom autoloader
        spl_autoload_register([$this,'init_autoload_system']);

        // Start the PHP session
        session_start();

        // load helpers
        $this->loadBasicHelper();

    }

    /**
     * Run the application
     *
     * Handles filters, initializes the controller, and includes the callable page.
     */
    public function run(): void {
        // Handle filters
        $filters = new Filters();

        if($filters->path_is_update()) {
            $path = $filters->applyRedirectPath();
        } else {
            $path = PATH;
        }

        // Initialize the controller
        $controller = new Controller($path);

        if($controller->callablePagePath != 'json'){
            // Extract data and include the callable page
            extract($controller->dataPage);
            @include_once $controller->callablePagePath;
        }
    }

    /**
     * Load environment variables from a .env file
     *
     * Depending on the environment, it loads the appropriate configuration variables.
     */
    protected function loadEnv(): void {
        $dotenv = Dotenv::createImmutable(getcwd());
        $dotenv->load();

        if($_ENV['ENVIRONNEMENT'] === 'PROD') { // Production environment
            error_reporting(0);

            define('DB_HOST',$_ENV['DB_PROD_HOST']);
            define('DB_USER',$_ENV['DB_PROD_USER']);
            define('DB_NAME',$_ENV['DB_PROD_NAME']);
            define('DB_PASS',$_ENV['DB_PROD_PASS']);
            define('BASE_PATH',$_ENV['BASE_PATH_PROD']);
            define('BASE_URI',$_ENV['BASE_URI_PROD']);

        } else { // Development environment
            define('DB_HOST',$_ENV['DB_DEV_HOST']);
            define('DB_USER',$_ENV['DB_DEV_USER']);
            define('DB_NAME',$_ENV['DB_DEV_NAME']);
            define('DB_PASS',$_ENV['DB_DEV_PASS']);
            define('BASE_PATH',$_ENV['BASE_PATH_DEV']);
            define('BASE_URI',$_ENV['BASE_URI_DEV']);
        }

        define("PATH", ($_SERVER['PATH_INFO'] ?? '/'));
    }

    /**
     * Initialize the vendor autoload
     *
     * Requires the vendor/autoload.php file.
     */
    protected function init_vendor_autoload(): void {
        require_once('vendor/autoload.php');
    }

    /**
     * Initialize the custom autoloader system
     *
     * @param string $className The name of the class to load.
     */
    protected function init_autoload_system($className) : void {
        $className = str_replace('\\',DIRECTORY_SEPARATOR,$className);
        $classPath = BASE_PATH.DIRECTORY_SEPARATOR.$className.'.php';

        if(is_file($classPath)) {
            require_once $classPath;
        }
    }


    /*
     * Load the systemHelper.
    */
    protected function loadBasicHelper() : void {
        $helperServiceInstance = new Helper();

        $helperServiceInstance->getFromSystem('viewSetup');
    }

}