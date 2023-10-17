<?php
namespace system\Services;

defined( 'PATH' ) || die(':)');

/**
 * The `Routes` class in the PlumePHP framework is responsible for managing user-defined routes.
 * It allows users to add routes and error routes, and provides a method to retrieve all registered routes.
 */
class Routes {

    /**
     * An array that holds all instances of the `Routes` class.
     * This allows for easy management of multiple instances.
     *
     * @var array
     */
    public static array $_interface;

    /**
     * An array to store user-defined routes.
     *
     * @var array
     */
    public static array $route = [];

    /**
     * An array to store user-defined error routes.
     *
     * @var array
     */
    public static array $errorRoute = [];

    /**
     * Constructor for the `Routes` class.
     * It adds the current instance of the class to the `_interface` array.
     */
    public function __construct() {
        self::$_interface[] = $this;
    }

    /**
     * Adds a new user-defined route.
     *
     * @param string $to The target route.
     * @param string $method The method or action associated with the route. Format : "Class::method"
     * @param string $type The HTTP request type (default is 'GET').
     */
    public static function addRoute(string $to, string $method, string $type = 'GET'): void {
        self::$route[$to] = $method;
    }

    /**
     * Adds a new user-defined error route.
     *
     * @param string $typeError The type of error (e.g., '404', '500').
     * @param string $path The path to the error page.
     */
    public static function addErrorRoute(string $typeError, string $path): void {
        self::$errorRoute[$typeError] = $path;
    }

    /**
     * Retrieves all registered user-defined routes.
     *
     * @return array An array containing all user-defined routes.
     */
    public static function getRoute(): array {
        return self::$route;
    }
}