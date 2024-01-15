<?php

namespace app\Controller;

use system\Responses\Response;

class Home
{
    public function index(int $id, int $id2) : Response {
        status(404);
        return Json([$id,$id2]);
    }

    public function redirect() : Response {
        return Redirect('/test');
    }
}