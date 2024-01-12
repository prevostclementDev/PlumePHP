<?php

    defined( 'PATH' ) || die(':)');

    /*
     * Utility formPosts Functions
     *
     * This section defines two utility functions for common tasks.
     * These functions can be used to simplify data validation and request method checking.
     */

    /**
     * postArrayCheck
     *
     * @param array $arrayRequire An associative array containing required POST parameters.
     * @return bool Returns true if all required POST parameters are set and not empty; otherwise, returns false.
     */
    function postArrayCheck(array $arrayRequire): bool {
        foreach ($arrayRequire as $key => $item) {
            if (!isset($_POST[$key]) || $_POST[$key] === '') {
                return false;
            }
        }
        return true;
    }

    /**
     * request_is
     *
     * @param string $request_method The HTTP request method to check against (e.g., 'POST', 'GET').
     * @return bool Returns true if the current HTTP request method matches the specified method; otherwise, returns false.
     */
    function request_is(string $request_method): bool {
        if ($_SERVER['REQUEST_METHOD'] === $request_method) {
            return true;
        }
        return false;
    }