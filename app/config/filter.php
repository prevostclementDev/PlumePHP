<?php

namespace app\config;
use system\Filters;

defined( 'PATH' ) || die(':)');

/*
 * Configuration: Filters
 *
 * This section is used to add filters before the Controllers.
 * Use filters like this: Filters::$filterBefore[{name}] = [{type}, {method}, {onRoute}];
 *
 * {type}: only allow 'PATH'.
 * {onRoute}:
 *  - '*' for all routes
 *  - '/' only on the / route
 *  - '/admin/*' only on routes starting with /admin/
 *
 * Example: Filters::$filterBefore['Auth'] = ['PATH', 'Auth::is_logged', '*'];
 *
 * Note: Filters are used to perform specific actions or checks before the control logic is executed. They are useful for authentication, data validation, and more.
 */