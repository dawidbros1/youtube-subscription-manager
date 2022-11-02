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

    public function getChannels($category_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM channels WHERE category_id=:category_id");
        $stmt->execute([
            'category_id' => $category_id,
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteChannelsByCategoryId($category_id)
    {
        $stmt = self::$pdo->prepare("DELETE FROM channels WHERE category_id=:category_id");

        $stmt->execute([
            'category_id' => $category_id,
        ]);
    }
}
