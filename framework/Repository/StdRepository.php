<?php

declare(strict_types=1);

namespace Phantom\Repository;

use Phantom\Repository\AbstractRepository;

class StdRepository extends AbstractRepository
{
    protected $table;

    public function __construct(string $table)
    {
        $this->table = $table;
        parent::__construct();
    }

    public function setTable(string $table)
    {
        $this->table = $table;
    }
}