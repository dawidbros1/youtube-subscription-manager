<?php

declare (strict_types = 1);

namespace App\Model;

class User
{
    private $id;
    private $username;
    private $categories;

    public function __construct($youtube)
    {
        $this->id = $youtube->id;
        $this->username = $youtube->snippet->title;
        $this->categories = (new Category())->findAll(['user' => $this->id]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getCategories()
    {
        return $this->categories;
    }
}
