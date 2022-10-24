<?php

declare (strict_types = 1);

namespace App\Model;

class User
{
    private $id;
    private $username;

    public function __construct($youtube)
    {
        $this->id = $youtube->id;
        $this->username = $youtube->snippet->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }
}
