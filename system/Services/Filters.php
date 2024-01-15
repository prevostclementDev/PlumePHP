<?php

namespace system\Services;

use system\Responses\Redirect;

defined( 'PATH' ) || die(':)');

/**
 * The `Filters` class in the PlumePHP framework handles pre-route filtering and redirections.
 * It allows for defining filters to be executed before certain routes and manages affected paths.
 */
class Filters {

    /**
     * An associative array of filters to be executed before specific routes.
     * Example:
     * ```
     * 'Auth' => ['PATH', 'Auth::is_logged', '/']
     * ```
     * The above entry indicates that the 'Auth::is_logged' function should be called before processing the '/' route.
     * The first element 'PATH' specifies that this is a filter for the 'PATH' route.
     *
     * @var array
     */
    public static array $filterBefore = array();

    /**
     * An associative array that keeps track of affected paths by filters.
     *
     * @var array
     */
    public array $filterAffected = array(
        'PATH' => [],
    );

    /**
     * Constructor for the `Filters` class.
     * It initiates filter processing by calling `applyFilterBefore`.
     */
    public function __construct(){

        require_once BASE_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'filter.php';

        $this->applyFilterBefore();
    }

    /**
     * Checks if there are filters affecting the 'PATH' route.
     *
     * @return bool Returns `true` if there are filters affecting the 'PATH' route; otherwise, `false`.
     */
    public function path_is_update() : bool {
        if(!empty($this->filterAffected['PATH'])) {
            return true;
        }
        return false;
    }

    /**
     * Applies redirection to the affected 'PATH' route.
     *
     * @return string Returns the redirected path as a string.
     */
    public function applyRedirectPath() : string {
        $redirect = new Redirect(
                $this->filterAffected['PATH'][count($this->filterAffected['PATH'])-1],
                false
        );
        return $redirect->render();
    }

    /**
     * Applies filters defined in `$filterBefore` and populates `$filterAffected`.
     */
    private function applyFilterBefore(): void
    {

        foreach (self::$filterBefore as $callable) {

            if(str_contains($callable[2],'*')) {

                if(strlen($callable[2]) > 1) {

                    $explodeActualPath = explode( explode('*',$callable[2] )[0] ,PATH);

                    $isEqualToCallable = $explodeActualPath[0] === substr($callable[2],0,-2);
                    $isEqualToPath = $explodeActualPath[0] === PATH && count($explodeActualPath) === 1;

                    if ( !$isEqualToCallable && $isEqualToPath ) {
                        continue;
                    }

                }

            } else if ( PATH != $callable[2] ) {

                continue;

            }

            $explodeCallable = explode('::',$callable[1]);

            $callableNamespace = 'app\Filters\\' . $explodeCallable[0];

            $filterClass = new $callableNamespace;
            $return = $filterClass->{$explodeCallable[1]}();

            if($return === true) {
                continue;
            }

            $this->filterAffected[$callable[0]][] = $return;

        }

    }

}