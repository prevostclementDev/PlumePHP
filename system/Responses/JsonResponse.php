<?php

namespace system\Responses;

/**
 * The `JsonResponse` class in the PlumePHP framework is responsible for sending JSON responses.
 * It allows you to create and send JSON-encoded data as responses.
 */
class JsonResponse {

    /**
     * An array containing the data to be included in the JSON response.
     *
     * @var array
     */
    protected array $data;

    /**
     * Constructor for the `JsonResponse` class.
     *
     * @param array $data An array containing the data to be included in the JSON response.
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * Render and send the JSON response to the client.
     */
    public function render(): void {
        echo $this->setup();
    }

    /**
     * Set up the JSON response, including the content type, and encode the data as JSON.
     *
     * @return bool|string Returns the JSON-encoded data as a string.
     */
    private function setup(): bool|string {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($this->data);
    }

}