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

    # Method returns all channels by caategory $ids
    public function getChannelsByCategoryIds(array $ids = [])
    {
        $inQuery = implode(',', array_fill(0, count($ids), '?'));
        $stmt = self::$pdo->prepare("SELECT * FROM channels WHERE category_id IN ($inQuery)");
        $stmt->execute($ids);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Method returns channels from category
    public function getChannelsByCategoryId($category_id)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM channels WHERE category_id=:category_id");
        $stmt->execute([
            'category_id' => $category_id,
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    # Method deletes channels from category
    public function deleteChannelsByCategoryId($category_id)
    {
        $stmt = self::$pdo->prepare("DELETE FROM channels WHERE category_id=:category_id");

        $stmt->execute([
            'category_id' => $category_id,
        ]);
    }
}
