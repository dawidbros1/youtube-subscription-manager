<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Exception\AppException;

class Config
{
    private $config;

    public function __construct($configuration)
    {
        $this->config = $configuration;
    }

    # Method get value of config
    public function get($path)
    {
        $output = $this->config;
        $array = explode(".", $path);

        foreach ($array as $name) {

            if (array_key_exists($name, $output)) {
                $output = $output[$name];
            } else {
                throw new AppException("Podany klucz konfiguracyjny [ $path ] nie istnieje");
            }
        }

        return $output;
    }
}
