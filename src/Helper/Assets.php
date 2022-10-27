<?php

declare (strict_types = 1);

namespace App\Helper;

class Assets
{
    private static $location;
    public static function initConfiguration($location)
    {
        self::$location = $location;
    }

    public static function get(string $path)
    {
        return self::$location . "public/" . $path;
    }
}
