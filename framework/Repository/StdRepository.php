<?php

declare (strict_types = 1);

namespace Phantom\Repository;

use Phantom\Repository\AbstractRepository;

class StdRepository extends AbstractRepository
{
    public function __construct(string $table)
    {
        $this->table = $table;
        parent::__construct();
    }
}
