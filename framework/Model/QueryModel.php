<?php

declare(strict_types=1);

namespace Phantom\Model;

use Phantom\Helper\Session;
use Phantom\Repository\StdRepository;

abstract class QueryModel
{
    protected $table;

    # Method adds record to database
    public function create()
    {
        (new StdRepository($this->table))->create($this);
    }

    # Method updates current object 
    public function update(array $update = [])
    {
        $this->escape();
        (new StdRepository($this->table))->update($this, $update);
        Session::success('Dane zostały zaktualizowane'); // Default value
        return true;
    }

    # Method delete record from database
    # if property ID is sets this record will we deleted
    # else current object will be deleted
    public function delete(?int $id = null)
    {
        $repository = new StdRepository($this->table);

        if ($id !== null) {
            $repository->delete((int) $id);
        } else {
            $repository->delete((int) $this->getId());
        }
    }

    // abstract method

    public abstract function escape();
    public abstract function getId();
}