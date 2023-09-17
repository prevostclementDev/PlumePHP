<?php

class Redirect {

    private string $url;
    private string $pathController;

    public function __construct(String $url,String $pathController,Bool $exec = true)
    {
        $this->url = $url;
        $this->pathController = $pathController;
        if($exec) {
            $this->exec();
        }
    }

    public function exec() : string {
        ?>
        <script>
            window.history.replaceState('redirect', 'redirect', '<?= $this->url ?>');
        </script>
        <?php
        return $this->pathController;
    }

}