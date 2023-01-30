<?php

declare(strict_types=1);

namespace Phantom\Repository;

use PDO;
use PDOException;
use Phantom\Exception\ConfigurationException;
use Phantom\Exception\StorageException;

abstract class AbstractRepository extends QueryRepository
{
    protected static $config;
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
}