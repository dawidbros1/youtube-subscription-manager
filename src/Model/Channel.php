<?php

declare(strict_types=1);

namespace App\Model;

use Phantom\Model\AbstractModel;
use Phantom\Repository\StdRepository;

class Channel extends AbstractModel
{
    private $id;
    private $category_id;
    private $channel_id;
    protected $table = "channels";

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setChannelId($channel_id)
    {
        $this->channel_id = $channel_id;
    }

    public function getChannelId()
    {
        return $this->channel_id;
    }
}