<?php

namespace system\Responses;

class View implements Response{

    /**
     * The base path for views.
     *
     * @var string
     */
    private string $page_path = BASE_PATH.'/app/views/';

    public function __construct(
        protected string $path,
        protected ?array $data = null
    ){}

    public function render()
    {
        if ( ! is_null($this->data)  ) {
            extract($this->data);
        }

        @include_once $this->getPage($this->path);
    }

    /**
     * Generates the full path to a view page based on the provided page name.
     *
     * @param string $page The page name.
     * @return string The full path to the view page.
     */
    private function getPage(string $page) : string {
        return $this->page_path . $page . '.php';
    }


}