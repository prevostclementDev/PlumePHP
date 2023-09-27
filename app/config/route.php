<?php

namespace app\config;
use \system\Services\Routes;

/*
 * Route Configuration
 *
 * This section defines routes for your application using the Routes class.
 * Each route maps a URL path to a specific controller method.
 * To add a new route, use Routes::addRoute() as follows:
 * Routes::addRoute('[URL]', '[Controller::Method]');
 *
 * Example: Routes::addRoute('/', 'Home::index');
 * This maps the root URL ('/') to the 'index' method of the 'Home' controller.
 */

Routes::addRoute('/','Home::index');
