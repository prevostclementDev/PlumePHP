<?php

namespace system\Controller;

use system\Responses\Response;
use system\Responses\View;
use system\Services\Routes;

defined( 'PATH' ) || die(':)');

/**
 * The `Controller` class in the PlumePHP framework is responsible for processing routes and triggering controller actions.
 * It handles route validation, controller execution, and error handling.
 */
class Controller {

    /**
     * The requested path.
     *
     * @var string
     */
    protected string $path;

    /**
     * An array of registered routes.
     *
     * @var array
     */
    protected array $route;

    /**
     * An array of error routes, with keys representing error types (e.g., '404') and values representing error page paths.
     *
     * @var array
     */
    protected array $route_error = [
        '404' => 'error/error'
    ];

    public Response $response;

    private static ?Controller $instance = null;

    /**
     * Constructor for the `Controller` class.
     *
     * @param string $path The requested path.
     */
    public function __construct(string $path) {

        $this->path = $path;

        $this->getSetRoutes();

    }

    public static function getInstance (string $path) : Controller {
        if( is_null( self::$instance ) ) {
            self::$instance = new Controller($path);
        }
        return self::$instance;
    }

    /**
     * Processes the requested page, including route validation, controller execution, and rendering.
     */
    public function getRequestPage() : Response
    {

        $valid = $this->is_valid_path();

        // if PATH is valid for the App
        if($valid[0]) {

            // Get method with class $valid[1] = value of array self::$route
            $callable = $this->getCallableMethod($valid[1]);

            // construct namespace of class
            $className = '\app\Controller\\'.$callable[0];

            // call class
            $controllerCallable = new $className;

            // $valid[2] = $args for the method.
            if(isset($valid[2])) {
                $this->response = $controllerCallable->{$callable[1]}(...$valid[2]);
            } else {
                $this->response = $controllerCallable->{$callable[1]}();
            }

            if(is_a($this->response,'system\Responses\Redirect')) {
                $this->path = $this->response->render();
                return $this->getRequestPage();
            }

            return $this->response;
        }

        return $this->PageNotFound();

    }

    /**
     * Returns the page path for the 'Page Not Found' error.
     *
     * @return Response The path to the 'Page Not Found' error page.
     */
    private function PageNotFound() : Response {
        // get 404 page error route
        return new View($this->route_error['404']);
    }

    /**
     * Splits a route into the controller class and method.
     *
     * @param string $path The route to be split.
     * @return array An array with two elements: the controller class and method.
     */
    private function getCallableMethod(string $path): array {
        return explode('::', $this->route[$path]);
    }

    /**
     * Validates whether the requested path matches a registered route.
     *
     * @return array An array indicating if the path is valid and additional data if applicable.
     */
    private function is_valid_path() : array {
        // If page exist in self::$route
        if(key_exists($this->path,$this->route)) {
            return array(true,$this->path);
        }

        // check for route with $args
        $pE = explode('/',$this->path);

        foreach ($this->route as $routePath => $routeMethod) {

            $rE = explode('/',$routePath);

            $args = [];

            if ( count($pE) === count($rE) ) {

                $equalSegement = 0;

                foreach ($rE as $key => $rSegment) {

                    if ( $rSegment === $pE[$key] ) {
                        $equalSegement++;
                        continue;
                    }

                    $newArgs = $this->is_valid_arg($rSegment,$pE[$key]);

                    if ( preg_match('/\(:(.+)\)/',$rSegment,$matches) == 1 && $newArgs !== null ) {
                        $args[] = $newArgs;
                        $equalSegement++;
                    }

                }

                if ( count($rE) === $equalSegement ) {
                    return array(true,$routePath,$args);
                }

            }

        }

        return array(false);
    }

    /**
     * Validates and processes arguments based on their type.
     *
     * @param string $type The argument type (e.g., '(:num)', '(:text)').
     * @param mixed $arg The argument value.
     * @return int|string|null The processed argument or null if it's not valid.
     */
    private function is_valid_arg(string $type, mixed $arg): int|string|null {
        switch ($type) {
            case '(:num)':
                $arg = intval($arg);
                if($arg !== 0) {
                    return $arg;
                }
                break;
            case '(:text)':
                return strval($arg);
            default:
                return null;
        }
        return null;
    }

    /**
     * Searches for a partial match in an array.
     *
     * @param array $arr The array to search in.
     * @param string $keyword The keyword to search for.
     * @return int|null The index of the partial match or null if not found.
     */
    private function array_search_partial(array $arr, string $keyword): ?int {
        foreach($arr as $index => $string) {
            if (str_contains($string, $keyword)) {
                return $index;
            }
        }
        return null;
    }

    /**
     * Retrieves and sets the registered routes from the 'app/config/route.php' file.
     */
    private function getSetRoutes(): void {
        require_once BASE_PATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'route.php';
        $this->route = Routes::getRoute();
    }

}