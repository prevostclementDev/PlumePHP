<?php

namespace app\Controller;

use system\Responses\Response;

class Home
{
    public function index(int $id, int $id2) : Response {
        var_dump($id,$id2);
        return View('app/index');
    }

    public function redirect() : Response {
        return Redirect('/test');
    }
}