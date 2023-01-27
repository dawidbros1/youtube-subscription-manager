<?php

declare (strict_types = 1);

use Phantom\Model\Config;

return new Config(
    [
        'project' => [
            'location' => "http://example.pl/folder/", // http://example.pl/
        ],
        'db' => [
            'host' => 'localhost',
            'database' => '',
            'user' => '',
            'password' => '',
        ],
        'env' => "dev"
    ]
);
