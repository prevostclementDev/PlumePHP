<?php

namespace system;

class Filters {

    protected array $filterBefore = array(
//        'Auth' => ['PATH','Auth::is_logged'],
    );

    public array $filterAffected = array(
        'PATH' => [],
    );

    public function __construct(){
        $this->applyFilterBefore();
    }

    public function path_is_update() : bool {
        if(!empty($this->filterAffected['PATH'])) {
            return true;
        }
        return false;
    }

    public function applyRedirectPath() : string {
        $redirect = new Redirect(
                substr(BASE_URI, 0, -1) . $this->filterAffected['PATH'][count($this->filterAffected['PATH']) - 1],
                $this->filterAffected['PATH'][count($this->filterAffected['PATH'])-1],
                false
        );
        return $redirect->exec();
    }

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