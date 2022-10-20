<?php

declare (strict_types = 1);

namespace Phantom\Component;

use Phantom\Exception\AppException;

class Component
{
    private static $path = "/../../templates/";
    # Method renders component
    public static function render(string $template, array $params = []): void
    {
        $namespace = "App\Component\\" . str_replace(".", "\\", $template);

        # Checks if class of component exists
        if (!class_exists($namespace)) {
            throw new AppException("Klasa [ $namespace ] nie istnieje");
        }

        $component = new $namespace;
        $file = __DIR__ . self::$path . $component->template;

        # Checks if template of component was created
        if (!file_exists($file)) {
            throw new AppException("Plik [ $file ] nie istnieje");
        }

        self::requireParams($component->require, $params); # Checks if required params was given

        $styles = Component::getStyles($params); # Loads styles

        foreach ($params as $key => $param) {
            if (!in_array($key, ['class', 'mt', 'col'])) {
                ${$key} = $param;
            }
        }

        include $file;
    }

    private static function getStyles(array $params): string
    {
        $mt = $params['mt'] ?? "mt-3";
        $class = $params['class'] ?? "";
        $col = $params['col'] ?? "col-12";

        return "$mt $class $col";
    }

    public static function requireParams(array $require, array $params)
    {
        foreach ($require ?? [] as $param) {
            if (!array_key_exists($param, $params)) {
                throw new AppException("Wymagany parametr [ $param ] nie został wprowadzony");
            }
        }
    }
}
