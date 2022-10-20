<?php

declare (strict_types = 1);

namespace App\Repository;

use PDO;
use Phantom\Repository\AbstractRepository;

class UserRepository extends AbstractRepository
{
    public function __construct()
    {
        $this->table = "users";
        parent::__construct();
    }

    # Method returns all emails from "users" table
    public function getEmails(): array
    {
        $stmt = self::$pdo->prepare("SELECT email FROM $this->table");
        $stmt->execute();
        $emails = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $emails;
    }
}
