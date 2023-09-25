<?php

namespace system\Services;

class Routes {

    public static array $_interface;

    public static array $route = [];
    public static array $errorRoute = [];

    public function __construct() {
        self::$_interface[] = $this;
    }

    public static function addRoute(string $to,string $method, string $type = 'GET'): void {
        self::$route[$to] = $method;
    }

    public static function addErrorRoute(string $typeError,string $path) : void {
        self::$errorRoute[$typeError] = $path;
    }

    public static function getRoute(){
        return self::$route;
    }

}