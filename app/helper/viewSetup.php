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