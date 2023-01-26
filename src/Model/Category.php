<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\CategoryRepository;
use App\Rules\CategoryRules;
use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;
use Phantom\Validator\Validator;

class Category extends AbstractModel
{
    protected $table = "categories";
    private $id;
    private $user;
    private $name;
    private $repository;
    private $channels = [];

    public function __construct(array $data = [])
    {
        $this->repository = new CategoryRepository();
        parent::__construct($data);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function _getChannelsByCategoryIds($ids)
    {
        return $this->repository->getChannelsByCategoryIds($ids);
    }
    public function _getChannels()
    {
        return $this->channels;
    }

    # Method delete category with content
    public function delete(?int $id = null)
    {
        $this->repository->deleteChannelsByCategoryId($this->id);
        parent::delete();
        Session::success("Grupa <b>" . $this->getName() . "</b> została usunięta");
    }

    public function create()
    {
        $validator = new Validator(['name' => $this->name], new CategoryRules());

        if ($validator->validate()) {
            parent::create();
            return true;
        }
    }

    public function update(array $data = [])
    {
        $this->set($data);

        $validator = new Validator($data, new CategoryRules());

        if ($validator->validate()) {
            parent::update(array_keys($data));
        }
    }

    # Method get channel from database
    public function loadChannels()
    {
        $result = $this->repository->getChannelsByCategoryId($this->getId());

        foreach ($result as $data) {
            $this->channels[] = new Channel($data);
        }
    }
}