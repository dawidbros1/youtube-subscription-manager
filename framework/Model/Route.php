<?php

declare (strict_types = 1);

namespace Phantom\Model;

use Phantom\Exception\AppException;
use Phantom\Htaccess;

class Route
{
    private $routes, $htaccess, $location;

    public function __construct(string $location)
    {
        $this->htaccess = new Htaccess();
        $this->location = $location;
    }

    # Method register route on every element in $array
    # array $array: [ 'action' => 'url', 'action' => 'url' ]
    # Example: $array = [
    #   'profile' => '/user/profile',
    #   'list' => '/users/list'
    # ]
    public function group(string $type, string $prefix, array $array)
    {
        foreach ($array as $action => $url) {
            $this->register($type, $prefix, $action, $url);
        }
    }

    # Method adds new route to $routes and adds RewriteRule to file .htaccess
    # string $prefix: It is prefix of controller name like a user|category. If is empty will be runs default type (general)
    # string $action: Which action from controller will be runs. If $action is empty will be run method index()
    # string $url: It is url which will be see on address bar. Example: user/profile | users/list
    public function register(string $type, string $prefix, string $action = "", string $url = "")
    {
        $url = $prefix . $url;
        $url = substr($url, 1);
        $array = explode("/", $url);
        $index = 1;

        foreach ($array as $string) {
            if (preg_match("/^{.+}$/", $string)) {
                if (str_contains($string, "_id") || $string == "{id}") {
                    $replace = "([0-9]+)";
                } else {
                    $replace = "(.)+";
                }

                $url = str_replace($string, $replace, $url);
                $name = substr($string, 1, -1);
                $action .= "&$name=$" . $index++;
            }
        }

        $fullUrl = $this->location . $url;

        # actions in GeneralController
        if (strlen($type) == 0) {
            $this->routes[$action] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?action=$action";
        }

        # action index() in AnyController
        if (strlen($type) != 0 && strlen($action) == 0) {
            $this->routes[$type] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?type=$type";
        }

        if (strlen($type) != 0 && strlen($action) != 0) {
            $this->routes[$type][$action] = $fullUrl;
            $line = "RewriteRule ^$url$ ./?type=$type&action=$action";
        }

        /* auto fill file .htaccess */
        $this->htaccess->write($line);
    }

    # Special route for home page without setting $name and $action
    public function homepage(string $name)
    {
        $this->routes[$name] = $this->location;
        $this->htaccess->write("RewriteRule DirectoryIndex .");
    }

    # Method returns value (address) of route like a ./?type=user&action=list
    public function get($path)
    {
        $output = $this->routes;
        $array = explode(".", $path);

        foreach ($array as $name) {

            if (array_key_exists($name, $output)) {
                $output = $output[$name];
            } else {
                throw new AppException("Podany klucz routingu [ $path ] nie istnieje");
            }
        }

        return $output;
    }
}
