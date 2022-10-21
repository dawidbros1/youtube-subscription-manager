<?php

declare (strict_types = 1);

use Phantom\Model\Route;

$route = new Route($location);

$route->homepage('home');

return $route;
