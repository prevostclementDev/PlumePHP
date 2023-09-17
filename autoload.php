<?php

    require_once 'config.php';

    function classDefaultImport($className) {
        if(file_exists('src/'.$className.'.php')) {
            require_once 'src/'.$className.'.php';
        }
    }

    function classControllerImport($className) {
        if(file_exists('src/Controller/'.$className.'.php')) {
            require_once 'src/Controller/'.$className.'.php';
        }
    }

    function classFilterImport($className) {
        if(file_exists('src/Filters/'.$className.'.php')) {
            require_once 'src/Filters/'.$className.'.php';
        }
    }

    function classModelsImport($className) {
        if(file_exists('src/Models/'.$className.'.php')) {
            require_once 'src/Models/'.$className.'.php';
        }
    }

    function autoloadhelper() {
        foreach (scandir(BASE_PATH.'/src/helper/') as $file){
            if(str_contains($file,'.php')) {
                require_once BASE_PATH.'/src/helper/'.$file;
            }
        }
    }


autoloadhelper();
spl_autoload_register('classDefaultImport');
spl_autoload_register('classControllerImport');
spl_autoload_register('classFilterImport');
spl_autoload_register('classModelsImport');

