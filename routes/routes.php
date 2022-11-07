<?php

declare (strict_types = 1);

$route = require_once "basic.php";

$route->register('authorization', '/authorization', 'saveAccessToken');
$route->register('authorization', '/logout', 'logout');
$route->register('', '/contact', 'contact');

$route->group('category', '/category', [
    'create' => "/create",
    'show' => "/video/{id}",
    'edit' => "/edit/{id}",
    'delete' => "/delete",

    'manage' => "/manage",
    'list' => "/subscription/{id}",
]);

$route->group('channel', '/channel', [
    'create' => "/create",
    'delete' => "/delete",
]);

return $route;
