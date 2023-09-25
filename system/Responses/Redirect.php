<?php

namespace system\Responses;

/**
 * The `Redirect` class in the PlumePHP framework is responsible for sending redirects to controllers in the 'app\Controller' directory.
 * It enables changing the browser's URL and triggering controller execution.
 */
class Redirect {

    /**
     * The target URL for the redirect.
     *
     * @var string
     */
    private string $url;

    /**
     * The path to the controller that should handle the redirect.
     *
     * @var string
     */
    private string $pathController;

    /**
     * Constructor for the `Redirect` class.
     *
     * @param string $url The target URL for the redirect.
     * @param string $pathController The path to the controller that should handle the redirect.
     * @param bool $exec Whether to execute the redirect immediately (default is true).
     */
    public function __construct(string $url, string $pathController, bool $exec = true)
    {
        $this->url = $url;
        $this->pathController = $pathController;

        if ($exec) {
            $this->exec();
        }
    }

    /**
     * Execute the redirect by changing the browser's URL.
     *
     * @return string Returns the path to the controller that should handle the redirect.
     */
    public function exec(): string {
        ?>
        <script>
            window.history.replaceState('redirect', 'redirect', '<?= $this->url ?>');
        </script>
        <?php
        return $this->pathController;
    }
}