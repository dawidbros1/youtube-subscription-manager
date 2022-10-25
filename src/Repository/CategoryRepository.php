<?php

declare (strict_types = 1);

namespace App\Repository;

use Phantom\Repository\AbstractRepository;

class CategoryRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->table = "categories";
        parent::__construct();
    }
}
