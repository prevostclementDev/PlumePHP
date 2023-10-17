<?php

namespace app\Controller;

class Home
{
    public function index() : array {
        return array('app/index',array("test"));
    }
}