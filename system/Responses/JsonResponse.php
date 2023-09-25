<?php

namespace system\Responses;

class JsonResponse {

    protected array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function render(): void {
        echo $this->setup();
    }

    private function setup(): bool|string {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($this->data);
    }

}