<?php

    require_once 'autoload.php';

    $filters = new Filters();

    if($filters->path_is_update()) {
        $path = $filters->applyRedirectPath();
    } else {
        $path = PATH;
    }

    $controller = new Controller($path);

    if($controller->callablePagePath != 'json'){
        extract($controller->dataPage);

        @include_once $controller->callablePagePath;
    }

