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

    public static function css(string $path)
    {
        return self::$location . "public/css/" . $path;
    }

    public static function js(string $path)
    {
        return self::$location . "public/js/" . $path;
    }

    public static function images(string $path)
    {
        return self::$location . "public/images/" . $path;
    }
}
