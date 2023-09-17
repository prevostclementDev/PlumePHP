<?php

    function checkpostarray(array $arrayRequire)  : bool {
        foreach ($arrayRequire as $key => $item){
            if(!isset($_POST[$key])) {
                return false;
            }
            if($_POST[$key] === '') {
                return false;
            }
        }
        return true;
    }

    function is_post_request() : bool{
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            return true;
        }
        return false;
    }

    function is_get_request() : bool{
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            return true;
        }
        return false;
    }