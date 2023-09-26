<?php

namespace system\Controller;

use system\Services\Routes;

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

    /**
     * The base path for views.
     *
     * @var string
     */
    private string $page_path = BASE_PATH.'/app/views/';

    /**
     * The path to the callable page.
     *
     * @var string
     */
    public string $callablePagePath;

    /**
     * An array to store data to be passed to the callable page.
     *
     * @var array
     */
    public array $dataPage = [];

    /**
     * Constructor for the `Controller` class.
     *
     * @param string $path The requested path.
     */
    public function __construct(string $path){

        $this->path = $path;

        $this->getSetRoutes();

        $this->getRequestPage();

    }

    /**
     * Processes the requested page, including route validation, controller execution, and rendering.
     */
    private function getRequestPage() : void {

        $valid = $this->is_valid_path();

        // if PATH is valid for the App
        if($valid[0]) {

            // Get method with class $valid[1] = value of array self::$route
            $callable = $this->getCallableMethod($valid[1]);

            // construct method
            $className = '\app\Controller\\'.$callable[0];

            // call method
            $controllerCallable = new $className;

            // $valid[2] = $args for the method.
            if(isset($valid[2])) {
                $newPageData = $controllerCallable->{$callable[1]}($valid[2]);
            } else {
                $newPageData = $controllerCallable->{$callable[1]}();
            }


            // If controller return is Redirect reload process
            if(is_a($newPageData,'Redirect')) {
                $this->path = $newPageData->exec();
                $this->redirect();
                return;
            }

            // If controller return is JsonResponse
            if(is_a($newPageData, 'JsonResponse')) {
                $newPageData->render();
                $this->callablePagePath = 'json';
                return;
            }

            // $newPageData[0] == path to view in folder app/views.
            $this->callablePagePath = $this->getPage($newPageData[0]);

            // $newPageData[1] = data for the page.
            if(isset($newPageData[1])) {
                $this->dataPage = $newPageData[1];
            }

            return;
        }

        $this->callablePagePath = $this->PageNotFound();

    }

    /**
     * Returns the page path for the 'Page Not Found' error.
     *
     * @return string The path to the 'Page Not Found' error page.
     */
    private function PageNotFound() : string {
        // get 404 page error route
        return $this->getPage($this->route_error['404']);
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
     * Generates the full path to a view page based on the provided page name.
     *
     * @param string $page The page name.
     * @return string The full path to the view page.
     */
    private function getPage(string $page) : string {
        return $this->page_path . $page . '.php';
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
        foreach ($this->route as $item => $value) {

            $pathExplode = explode('/',$this->path);

            // if we have $args in URL.
            if(str_contains($item,'(:')) {
                $itemExplode = explode('/',$item);

                if(count($pathExplode) != count($itemExplode)) {
                    continue;
                }

                $search = 0;
                for ( $i = 0; $i < count($itemExplode) - 1 ; $i++ ) {
                    if($itemExplode[$i] === $pathExplode[$i]) {
                        $search++;
                    }
                }

                if($search == count($itemExplode) - 1) {

                    $args = $this->is_valid_arg(
                        $itemExplode[$this->array_search_partial($itemExplode,'(:')],
                        $pathExplode[$this->array_search_partial($itemExplode,'(:')]
                    );

                    if($args !== null){

                        return array(true,$item,$args,);

                    }

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
     * Redirects to the requested page.
     */
    private function redirect(): void {
        // reload process
        $this->getRequestPage();
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