<?php

    function View(string $path,array $data = null) : \system\Responses\View {
        return new \system\Responses\View($path,$data);
    }

    function Json(array $data = null) : \system\Responses\Json {
        return new \system\Responses\Json($data);
    }

    function Redirect(string $path) : \system\Responses\Redirect {
        return new \system\Responses\Redirect($path);
    }