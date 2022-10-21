<?php

declare (strict_types = 1);

$route = require_once "basic.php";

$route->register('authorization', '/authorization', 'saveAccessToken');
$route->register('authorization', '/logout', 'logout');

// $route->group('test', "/test", [
//     'show' => "/show/{id}",
//     'show2' => "/show/{id}/{abc}/{category_id}",
// ]);

return $route;
