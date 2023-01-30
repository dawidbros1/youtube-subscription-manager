<?php

declare(strict_types=1);

namespace Phantom\Repository;

use PDO;
use Phantom\Exception\StorageException;
use Throwable;

abstract class QueryRepository
{
    protected static $pdo;
    protected $table;

    # Method returns records from database
    # if (fetch == "one") one record => fetch()
    # if (fetch == "all") all records => fetchAll()
    public function find(array $conditions, $options, string $fetch = "one")
    {
        if (!empty($conditions)) {
            $format = $this->formatConditions($conditions);
            $stmt = self::$pdo->prepare("SELECT * FROM $this->table WHERE $format $options");
        } else {
            $stmt = self::$pdo->prepare("SELECT * FROM $this->table $options");
        }

        $stmt->execute($conditions);

        if ($fetch == "one") {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else if ($fetch == "all") {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $data;
    }

    # Method returns a lot of records from database
    public function findALl(array $conditions, $options): ?array
    {
        return $this->find($conditions, $options, "all");
    }

    // # Method adds record to database
    public function create($object)
    {
        $object->escape();
        $data = $object->_getData();

        $params = "";
        $values = "";

        # to right format params and $values
        for ($i = 0; $i < count($data); $i++) {
            $params = $params . "" . key($data) . ($i == count($data) - 1 ? "" : ", ");
            $values = $values . ":" . key($data) . ($i == count($data) - 1 ? "" : ", ");
            next($data);
        }

        try {
            $sql = "INSERT INTO $this->table ($params) VALUES ($values)";
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute($data);
        } catch (Throwable $e) {
            throw new StorageException('Nie udało się dodać nowej zawartości', 400, $e);
        }
    }

    # Method updates the record in the database by ID
    # Method require that input parameter was object
    public function update($object, $update = [])
    {
        $params = "";
        $data = $object->_getData($update); # From object will be get all data

        for ($i = 0; $i < count($data); $i++) { # and next $params will be prepare to right format
            $params = $params . key($data) . "=:" . key($data) . ($i == count($data) - 1 ? "" : ", ");
            next($data);
        }

        $sql = "UPDATE $this->table SET $params WHERE id=:id"; # Condition is always id=:id
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($data);
    }

    # Method deletes a record from the database by ID
    public function delete(int $id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        self::$pdo->prepare($sql)->execute(['id' => $id]);
    }

    # Method format conditions to WHERE in SELECT [ use in method get() ]
    # from ['id' => 5, 'name' => "bike"] to "id=:id AND name=:name"
    private function formatConditions(array $input)
    {
        $conditions = "";

        foreach ($input as $key => $value) {
            $conditions .= $key . "=:" . $key . " AND ";
        }

        if ($conditions != "") {
            $conditions = substr($conditions, 0, -5);
        }

        return $conditions;
    }
}