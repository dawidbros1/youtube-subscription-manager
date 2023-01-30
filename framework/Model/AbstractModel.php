<?php

declare(strict_types=1);

namespace Phantom\Model;

use Phantom\Helper\Session;
use Phantom\Model\QueryModel;

abstract class AbstractModel extends QueryModel
{
    protected static Config $config;

    public static function initConfiguration($config)
    {
        self::$config = $config;
    }

    public function __construct(array $data = [])
    {
        $this->set($data);
    }

    public function set($data)
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $method = $this->convertToCamelCase('set' . ucfirst($key));

                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }
    }

    # Method return values of a lot of properties
    public function _getData(array $properties = [])
    {
        if (empty($properties)) {
            $methods = get_class_methods(get_class($this));

            foreach ($methods as $method) {
                if (substr($method, 0, 3) === 'get') {
                    $key = lcfirst(substr($method, 3));
                    $key = $this->convertToSnakeCase($key);
                    $output[$key] = $this->$method();
                }
            }
        } else {
            $properties[] = "id";

            foreach ($properties as $name) {
                $method = 'get' . $this->convertToCamelCase($name);

                if (method_exists($this, $method)) {
                    $output[$name] = $this->$method();
                }
            }
        }

        return $output ?? [];
    }

    # Method do htmlentities on every property
    public function escape()
    {
        $properties = get_object_vars($this);

        foreach ($properties as $index => $key) {
            if (property_exists($this, $key)) {
                $this->$key = htmlentities((string) $this->$key);
            }
        }
    }

    # Method hash name of file to create unique file name
    public function hashFile($file)
    {
        $type = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name = hash('sha256', date('Y-m-d H:i:s') . "_" . $file['name']);
        $file['name'] = $name . '.' . $type;
        return $file;
    }

    # Method upload file on server
    protected function uploadFile($path, $FILE): bool
    {
        $location = $path . basename($FILE["name"]);

        if (move_uploaded_file($FILE["tmp_name"], $location)) {
            return true;
        } else {
            Session::set('error', 'Przepraszamy, wystąpił problem w trakcie wysyłania pliku');
            return false;
        }
    }

    # $url - Project location as "/.."
    public function _getLocation()
    {
        # Project location => http://localhost/php-start/
        $location = self::$config->get('project.location');

        # Current URL => http://localhost/php-start/user/profile/update
        $url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

        # diff => user/profile/update
        $diff = str_replace($location, "", $url);

        $array = explode("/", $diff);
        $array = array_map(fn($element) => "/..", $array);
        array_pop($array); # We needs pop one element
        $output = implode("", $array);
        $output = ".$output/";

        return $output;
    }
    private function convertToSnakeCase($string)
    {
        $string = preg_replace('/([A-Z])/', '_$1', $string);
        return strtolower($string);
    }

    private function convertToCamelCase($string)
    {
        $string = ucwords($string, '_');
        return str_replace('_', '', $string);
    }
}