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
        'mail' => [
            'email' => 'example@domain',
        ],
        'upload' => [
            'path' => [
                'avatar' => 'uploads/images/avatar/',
            ],
        ],
        'default' => [
            'path' => [
                'avatar' => 'public/images/avatar.png',
                'medium' => 'public/images/SocialMedia/',
            ],
            'route' => [
                'home' => 'home', // page after login
                'logout' => 'authorization', // page after logout
            ],
            'hash' => [
                'method' => 'sha256', // sha25 || md5 ...
            ],
        ],
        'reCAPTCHA' => [
            'key' => [
                'side' => '',
                'secret' => '',
            ],
        ],
    ]
);
