<?php

class JsonResponse {

    protected $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function render(){
        echo $this->setup();
    }

    private function setup(){
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($this->data);
    }

}