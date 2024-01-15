<?php

    function status(int $code): void {
        http_response_code($code);
    }

    function setHeaderWithArray(array $headers): void {
        foreach ( $headers as $key => $value ) {
               header( $key.': ' . $value );
        }
    }

    function setHeader($key,$value) : void {
        header($key . ': ' . $value);
    }