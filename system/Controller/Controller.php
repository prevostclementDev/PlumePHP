<?php

class Controller {

    protected string $path;
    protected array $route = [
        '/' => 'Home::index',
    ];
    protected array $route_error = [
        '404' => 'error/error'
    ];

    private string $page_path;

    public string $callablePagePath;
    public array $dataPage = [];

    public function __construct(string $path){
        $this->path = $path;
        $this->page_path = BASE_PATH.'/views/';
        $this->getRequestPage();
    }

    private function getRequestPage() : void {
        $valid = $this->is_valid_path();
        if($valid[0]) {

            $callable = $this->getCallableMethode($valid[1]);
            $controllerCallable = new $callable[0];
            if(isset($valid[2])) {
                $newPageData = $controllerCallable->{$callable[1]}($valid[2]);
            } else {
                $newPageData = $controllerCallable->{$callable[1]}();
            }

            if(is_a($newPageData,'Redirect')) {
                $this->path = $newPageData->exec();
                $this->redirect();
                return;
            }

            if(is_a($newPageData, 'JsonResponse')) {
                $newPageData->render();
                $this->callablePagePath = 'json';
                return;
            }

            $this->callablePagePath = $this->getPage($newPageData[0]);

            if(isset($newPageData[1])) {
                $this->dataPage = $newPageData[1];
            }

            return;
        }
        $this->callablePagePath = $this->PageNotFound();
    }

    private function PageNotFound() : string {
        return $this->getPage($this->route_error['404']);
    }

    private function getCallableMethode($path): array {
        return explode('::',$this->route[$path]);
    }

    private function getPage(string $page) : string {
        return $this->page_path.$page.'.php';
    }

    private function is_valid_path() : array{
        if(key_exists($this->path,$this->route)) {
            return array(true,$this->path);
        }

        foreach ($this->route as $item => $value) {
            $pathExplode = explode('/',$this->path);
            if(str_contains($item,'(:')) {
                $itemExplode = explode('/',$item);

                if(count($pathExplode) != count($itemExplode)) {
                    return array(false);
                }

                $search = 0;
                for ( $i = 0; $i < count($itemExplode) - 1 ; $i++ ) {
                    if($itemExplode[$i] === $pathExplode[$i]) {
                        $search++;
                    }
                }

                if($search == count($itemExplode) - 1) {
                    $args = $this->is_valid_arg($itemExplode[$this->array_search_partial($itemExplode,'(:')],$pathExplode[$this->array_search_partial($itemExplode,'(:')]);
                    if($args !== null){
                        return array(true,$item,$args,);
                    }
                }

            }
        }

        return array(false);

    }

    private function is_valid_arg($type,$arg)
    {
        switch ($type) {
            case '(:num)':
                $arg = intval($arg);
                if($arg !== 0) {
                    return $arg;
                }
                break;
            case '(:text)':
                $arg = strval($arg);
                return $arg;
            default:
                return null;
        }
        return null;
    }

    private function redirect(): void {
        $this->getRequestPage();
    }

    private function array_search_partial($arr, $keyword) {
        foreach($arr as $index => $string) {
            if (str_contains($string, $keyword))
                return $index;
        }
    }

}