<?php

    defined( 'PATH' ) || die(':)');

    /**
     * This function includes a PHP file from the "app/views/includes/" directory based on the specified part name.
     *
     * @param string $part The name of the PHP file (without the '.php' extension) to include.
     * @return void
     */

    // Usage:
    // To include a file named "example" in the "app/views/includes/" directory, call the function like this:
    // includePart('example');
    function includePart(string $part): void {
        // Define the base path for the application.
        $pathInclude =
            BASE_PATH .                          // The base path of the application
            DIRECTORY_SEPARATOR .                // Platform-independent directory separator
            'app' .                              // Subdirectory: 'app'
            DIRECTORY_SEPARATOR .                // Directory separator
            'views' .                            // Subdirectory: 'views'
            DIRECTORY_SEPARATOR .                // Directory separator
            'includes' .                         // Subdirectory: 'includes'
            DIRECTORY_SEPARATOR;                 // Directory separator

        // Build the full path to the file to be included.
        $pathFile = $pathInclude . $part . '.php';

        // Check if the file exists before attempting to include it.
        if (is_file($pathFile)) {
            // Use @ to suppress errors that might occur during inclusion.
            @include_once $pathFile;
        }
    }

    /**
     * This function includes a PHP function from some views on a layout.
     *
     * @param string $contentName The part of contentName (without the '.php' extension) to include.
     * @return void
     */

    // Usage :
    // To include a function content_{$contentName} in the app/views/includes directory.
    // Before request the layout page
    function theContentInLayout(string $contentName): void
    {
        $function_name = 'content_'.$contentName;

        if(function_exists($function_name)) {
            $function_name();
        }

    }

    /**
     * This function includes layout after declare function part
     *
     * @param string $layoutName The layout name $layoutName (without the '.php' extension) to include.
     * @return void
     */

    // Usage :
    // Request layout page after create function content
    function thisPageOnLayout(string $layoutName): void {
        // Define the base path for the application.
        $pathInclude =
            BASE_PATH .                          // The base path of the application
            DIRECTORY_SEPARATOR .                // Platform-independent directory separator
            'app' .                              // Subdirectory: 'app'
            DIRECTORY_SEPARATOR .                // Directory separator
            'views' .                            // Subdirectory: 'views'
            DIRECTORY_SEPARATOR .                // Directory separator
            'layouts' .                         // Subdirectory: 'layouts'
            DIRECTORY_SEPARATOR;                 // Directory separator

        // Build the full path to the file to be included.
        $pathFile = $pathInclude . $layoutName . '.php';

        // Check if the file exists before attempting to include it.
        if (is_file($pathFile)) {
            // Use @ to suppress errors that might occur during inclusion.
            @include_once $pathFile;
        }
    }