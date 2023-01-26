<?php

declare(strict_types=1);

namespace Phantom\Query;

use Phantom\Helper\Session;
use Phantom\Repository\StdRepository;

abstract class QueryModel
{
    protected $table;

    # Method to find one record from database
    # array $conditions: ['id' => 5, 'name' => "bike"]
    # string $options: ORDER BY column_name ASC|DESC
    # $namespace: which of class will be created object
    public function find(array $conditions, string $namaspace = null, string $options = "")
    {
        $repository = new StdRepository($this->table);
        $namaspace = $namaspace == null ? get_class($this) : $namaspace;

        if ($data = $repository->find($conditions, $options)) {
            return new $namaspace($data);
        }

        return null;
    }

    # Method to find one record from database by ID
    public function findById($id, $namaspace = null, string $options = "")
    {
        $namaspace = $namaspace == null ? get_class($this) : $namaspace;
        return $this->find(['id' => $id], $namaspace, $options);
    }

    # Method to find a lot of record from database
    public function findAll(array $conditions, $namaspace = null, string $options = "")
    {
        $repository = new StdRepository($this->table);
        $namaspace = $namaspace == null ? get_class($this) : $namaspace;
        $data = $repository->findAll($conditions, $options);

        if ($data) {
            foreach ($data as $item) {
                $output[] = new $namaspace($item);
            }
        }

        return $output ?? [];
    }

    # Method adds record to database
    # if object was validated earlier we can skip validate in this method
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