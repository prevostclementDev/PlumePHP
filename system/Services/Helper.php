<?php
namespace system\Services;

defined( 'PATH' ) || die(':)');

/**
 * The `Helper` class in the PlumePHP framework is responsible for loading helper functions from the 'app/helper' directory.
 * It allows for loading specific helper files or loading them dynamically as needed.
 */
class Helper
{
    /**
     * The base path for the 'app/helper' directory.
     *
     * @var string
     */
    protected string $helperPath = BASE_PATH.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'helper'.DIRECTORY_SEPARATOR;

    /**
     * The base path for the 'system/helper' directory.
     *
     * @var string
     */
    protected string $helperPathSystem = BASE_PATH.DIRECTORY_SEPARATOR.'system'.DIRECTORY_SEPARATOR.'systemHelper'.DIRECTORY_SEPARATOR;


    /**
     * Constructor for the `Helper` class.
     * If a helper name is provided, it calls the `get` method to load the specified helper.
     *
     * @param string|null $helperName The name of the helper to load (optional).
     */
    public function __construct(?string $helperName = null) {
        if ($helperName != null) {
            $this->get($helperName);
        }
    }

    /**
     * Load a specific helper by its name.
     *
     * @param string $helperName The name of the helper to load.
     * @return static The current instance of the `Helper` class.
     */
    public function get(string $helperName): static {
        if (is_file($this->helperPath.$helperName.'.php')) {
            require_once $this->helperPath.$helperName.'.php';
        }
        return $this;
    }

    /**
     * Load a specific helper by its name from system.
     *
     * @param string $helperName The name of the helper to load.
     * @return static The current instance of the `Helper` class.
     */
    public function getFromSystem(string $helperName): static
    {
        if (is_file($this->helperPathSystem.$helperName.'.php')) {
            require_once $this->helperPathSystem.$helperName.'.php';
        }
        return $this;
    }
}