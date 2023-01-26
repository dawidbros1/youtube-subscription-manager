<?php

declare(strict_types=1);

$route = require_once "basic.php";

$route->register('authorization', '/authorization', 'saveAccessToken');
$route->register('authorization', '/logout', 'logout');

$route->group('category', '/category', [
    'create' => "/create",
    'list' => "/list",
    'edit' => "/edit/{id}",
    'delete' => "/delete",
]);

$route->group('channel', '/channel', [
    'create' => "/create",
    'delete' => "/delete",
]);

$route->register('video', "/category/video/{id}");

$route->register('youtube', "/youtube/subscription/list/{id}");

return $route;