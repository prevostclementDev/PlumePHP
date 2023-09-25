<?php

namespace system;

use system\Responses\Redirect;

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
    protected array $filterBefore = array(
//        'Auth' => ['PATH','Auth::is_logged','/'],
    );

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
                substr(BASE_URI, 0, -1) . $this->filterAffected['PATH'][count($this->filterAffected['PATH']) - 1],
                $this->filterAffected['PATH'][count($this->filterAffected['PATH'])-1],
                false
        );
        return $redirect->exec();
    }

    /**
     * Applies filters defined in `$filterBefore` and populates `$filterAffected`.
     */
    private function applyFilterBefore(): void
    {
        foreach ($this->filterBefore as $callable) {
            $return = call_user_func($callable[1]);
            if($return === true) {
                continue;
            }
            $this->filterAffected[$callable[0]][] = $return;
        }
    }

}