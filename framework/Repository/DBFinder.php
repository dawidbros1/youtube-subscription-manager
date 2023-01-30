<?php

declare(strict_types=1);

namespace Phantom\Repository;

use Phantom\Exception\AppException;
use Phantom\Repository\StdRepository;

class DBFinder
{
   private static $instance = null;
   private $repository;

   private function __construct(string $table)
   {
      $this->repository = new StdRepository($table);
   }

   public function setTable(string $table)
   {
      $this->repository->setTable($table);
   }

   # Method to find one record from database
   # array $conditions: ['id' => 5, 'name' => "bike"]
   # string $options: ORDER BY column_name ASC|DESC
   # $namespace: which of class will be created object
   public function find(array $conditions, string $namaspace, string $options = "")
   {

      if ($data = $this->repository->find($conditions, $options)) {
         return new $namaspace($data);
      }

      return null;
   }

   # Method to find one record from database by ID
   public function findById($id, $namaspace, string $options = "")
   {
      return $this->find(['id' => $id], $namaspace, $options);
   }

   # Method to find a lot of record from database
   public function findAll(array $conditions, $namaspace, string $options = "")
   {
      $data = $this->repository->findAll($conditions, $options);

      if ($data) {
         foreach ($data as $item) {
            $output[] = new $namaspace($item);
         }
      }

      return $output ?? [];
   }

   public static function getInstance(?string $table = null)
   {
      if (self::$instance == null) {
         if ($table == null) {
            throw new AppException("Table name was not entered");
         }
         self::$instance = new DBFinder($table);
      } else if ($table != null) {
         self::$instance->setTable($table);
      }

      return self::$instance;
   }
}