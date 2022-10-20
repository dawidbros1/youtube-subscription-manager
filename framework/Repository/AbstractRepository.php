<?php

declare (strict_types = 1);

namespace Phantom\Repository;

use PDO;
use PDOException;
use Phantom\Exception\ConfigurationException;
use Phantom\Exception\StorageException;
use Throwable;

abstract class AbstractRepository
{
    protected static $pdo;
    protected static $config;
    protected $table;

    public static function initConfiguration($config)
    {
        self::$config = $config;
    }

    public function __construct()
    {
        try {
            $this->validateConfig(self::$config);
            $this->createConnection(self::$config);
        } catch (PDOException $e) {
            throw new StorageException('Connection error');
        }
    }

    # Method creates connection to database
    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        self::$pdo = new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }

    # Method validates $config
    private function validateConfig(array $config): void
    {
        if (
            empty($config['database']) ||
            empty($config['host']) ||
            empty($config['user']) ||
            !isset($config['password'])
        ) {
            throw new ConfigurationException('Storage configuration error');
        }
    }

    # Method returns records from database
    # if (fetch == "one") one record => fetch()
    # if (fetch == "all") all records => fetchAll()
    public function get(array $conditions, $options, string $fetch = "one")
    {
        $format = $this->formatConditions($conditions);
        $stmt = self::$pdo->prepare("SELECT * FROM $this->table WHERE $format $options");
        $stmt->execute($conditions);

        if ($fetch == "one") {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
        } else if ($fetch == "all") {
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $data;
    }

    # Method returns a lot of records from database
    public function getAll(array $conditions, $options): ?array
    {
        return $this->get($conditions, $options, "all");
    }

    # Method adds record to database
    public function create($object)
    {
        $object->escape();
        $data = $object->getArray($object->fillable);

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
    public function update($object)
    {
        $params = "";
        $data = $object->getArray($object->fillable); # From object will be get all data

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
