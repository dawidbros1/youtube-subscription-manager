<?php

declare (strict_types = 1);

namespace App\Model;

use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;

class Category extends AbstractModel
{
    private $channels = [];
    public $fillable = ['id', 'user', 'name'];

    # Method delete category with content
    public function delete(?int $id = null)
    {
        $this->repository->deleteChannelsByCategoryId($this->id);
        parent::delete();
        Session::success("Grupa <b>" . $this->get('name') . "</b> została usunięta");
    }

    public function update(array $toValidate = [], bool $validate = true)
    {
        if (parent::update($toValidate, $validate) == false) {
            Session::error(Session::get('error:name:between', true));
        }
    }

    # Method get channel from database
    public function loadChannels()
    {
        $data = $this->repository->getChannels($this->get('id'));

        foreach ($data as $channel) {
            $this->channels[] = new Channel($channel, false);
        }
    }

    public function getChannels()
    {
        return $this->channels;
    }
}
