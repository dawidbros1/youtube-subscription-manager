<?php

declare (strict_types = 1);

namespace App\Model;

use Phantom\Model\AbstractModel;
use Phantom\Repository\StdRepository;

class Channel extends AbstractModel
{
    public $fillable = ['id', 'category_id', 'channelId'];

    public function __construct(array $data = [], bool $stdRepository = true)
    {
        parent::__construct($data, false);

        if ($stdRepository) {
            $this->repository = new StdRepository('channels');
        }
    }
}
