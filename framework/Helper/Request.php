<?php

declare (strict_types = 1);

namespace Phantom\Helper;

class Request
{
    private $get = [];
    private $post = [];
    private $server = [];
    private $files = [];

    public function __construct(array $get, array $post, array $server, array $files)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
        $this->files = $files;
    }

    # Method returns value of parameter from request for current request method
    public function param(string $name, $default = null)
    {
        if ($this->isPost()) {
            return $this->postParam($name, $default);
        } else {
            return $this->getParam($name, $default);
        }
    }

    # Method checks if request method is post
    # If $names != [], method require to exists all post parameters in $names and next returns values of them
    public function isPost(array $names = [])
    {
        if ($status = $this->server['REQUEST_METHOD'] === 'POST') {
            if (!empty($names) && ($status = $this->hasPostNames($names, false))) {
                if (count($data = $this->postParams($names)) == 1) {
                    return $data[$names[0]];
                } else {
                    return $data;
                }
            }
        }
        return $status;
    }

    # Method returns array of values from post request
    public function postParams(array $names)
    {
        foreach ($names as $name) {
            $output[$name] = $this->postParam($name);
        }

        return $output ?? [];
    }

    # Method returns value from post request
    public function postParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }

    # Method checks if exists all parameters from post request contained in the variable "names"
    # if (returnData == true) method returns values of parameters instead of status
    public function hasPostNames(array $names, bool $returnData = true)
    {
        foreach ($names as $name) {
            if ($this->hasPostName($name, false) === false) {
                return false;
            }
        }

        return $returnData ? $this->postParams($names) : true;
    }

    # Method checks if exists input parameter in post request
    # if (returnData == true) method returns value of parameter instead of status
    public function hasPostName(string $name, bool $returnData = true)
    {
        if (!isset($this->post[$name])) {return false;}
        return $returnData ? $this->postParam($name) : true;
    }

    # This same description to get methods
    public function isGet(array $names = [])
    {
        if ($status = $this->server['REQUEST_METHOD'] === 'GET') {
            if (!empty($names) && ($status = $this->hasGetNames($names, false))) {
                if (count($data = $this->getParams($names)) == 1) {
                    return $data[$names[0]];
                } else {
                    return $data;
                }
            }
        }
        return $status;
    }

    public function getParams(array $names)
    {
        foreach ($names as $name) {
            $output[$name] = $this->getParam($name);
        }

        return $output ?? [];
    }

    public function getParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    public function hasGetNames(array $names, bool $returnData = true)
    {
        foreach ($names as $name) {
            if ($this->hasGetName($name, false) === false) {
                return false;
            }
        }

        return $returnData ? $this->getParams($names) : true;
    }

    public function hasGetName(string $name, bool $returnData = true)
    {
        if (!isset($this->get[$name])) {return false;}
        return $returnData ? $this->getParam($name) : true;
    }

    # Method returns QUERY_STRING
    public function queryString(): string
    {
        return $this->server['QUERY_STRING'];
    }

    # Method returns file
    public function file(string $name, $default = null)
    {
        return $this->files[$name] ?? $default;
    }

    # Method returns current url
    public function url()
    {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    public function lastPage()
    {
        return $this->server['HTTP_REFERER'];
    }
}
