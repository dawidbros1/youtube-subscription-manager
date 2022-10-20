<?php

declare (strict_types = 1);

namespace Phantom;

class RedirectToRoute
{
    private $route, $to, $params;
    public function __construct($route, $to, $params)
    {
        $this->route = $route;
        $this->to = $to;
        $this->params = $params;
    }

    # Method redirect to "$to" page
    # $to: ./?type=registration
    # $params: [
    #   'email' => "email.example.com"
    #   'username => "example"
    # ]
    public function redirect(): void
    {
        $location = $this->route->get($this->to);

        if (count($this->params)) {
            $queryParams = [];
            foreach ($this->params as $key => $value) {
                if (gettype($value) == "integer") {
                    $queryParams[] = urlencode($key) . '=' . $value;
                } else {
                    $queryParams[] = urlencode($key) . '=' . urlencode($value);
                }
            }

            $location .= ($queryParams = "?" . implode('&', $queryParams));
        }

        if ($location == "") {
            $location = "."; // for homepage
        }

        header("Location: " . $location);
        exit();
    }
}
