<?php

declare (strict_types = 1);

use Phantom\Model\Route;

$route = new Route($location);

$route->homepage('home');

$route->group("", "", [
    'policy' => "/policy",
    'contact' => "/contact",
    'regulations' => "/regulations",
]);

$route->register("registration", "/register");
$route->register("authorization", "/login");

$route->group('user', "/user", [
    'logout' => "/logout",
    'profile' => "/profile",
    'update' => "/profile/update",
]);

$route->group('passwordRecovery', "/password", [
    'forgot' => "/forgot",
    'reset' => "/reset",
]);

return $route;
