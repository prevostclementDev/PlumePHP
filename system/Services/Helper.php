<?php

namespace system\Services;

class Helper
{

    protected string $helperPath = BASE_PATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR;

    public function __construct(string $helperName = null) {

        if($helperName != null) {
            $this->get($helperName);
        }

    }

    public function get(string $helperName): static {
        if(is_file($this->helperPath.$helperName.'.php')) {
            require_once $this->helperPath.$helperName.'.php';
        }
        return $this;
    }

}