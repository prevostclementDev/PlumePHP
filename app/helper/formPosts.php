<?php

    function postArrayCheck(array $arrayRequire)  : bool {
        foreach ($arrayRequire as $key => $item){
            if(!isset($_POST[$key]) || $_POST[$key] === '') {
                return false;
            }
        }
        return true;
    }

    // POST OR GET
    function request_is(string $request_method) : bool{
        if($_SERVER['REQUEST_METHOD'] === $request_method){
            return true;
        }
        return false;
    }