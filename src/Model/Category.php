<?php

declare (strict_types = 1);

namespace App\Model;

use Phantom\Helper\Session;
use Phantom\Model\AbstractModel;

class Category extends AbstractModel
{
    public $fillable = ['id', 'user', 'name'];

    public function delete(?int $id = null)
    {
        parent::delete();
        Session::success("Grupa " . $this->get('name') . " została usunięta");
    }
}
