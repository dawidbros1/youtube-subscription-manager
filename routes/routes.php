<?php

declare (strict_types = 1);

$route = require_once "basic.php";

$route->register('authorization', '/authorization', 'saveAccessToken');
$route->register('authorization', '/logout', 'logout');
$route->register('', '/contact', 'contact');

$route->group('category', '/category', [
    'list' => "/list",
    'manage' => "/manage",
    'create' => "/create",
    'edit' => "/edit/{id}",
    'delete' => "/delete/{id}",
    'show' => "/show/{id}",
]);

return $route;
