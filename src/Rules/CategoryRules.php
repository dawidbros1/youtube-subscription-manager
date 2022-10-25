<?php

declare (strict_types = 1);

namespace App\Rules;

use Phantom\Model\AbstractRules;

class CategoryRules extends AbstractRules
{
    public function rules()
    {
        $this->createRule('name', ['between' => ['min' => 3, "max" => 255]]);
    }

    public function messages()
    {
        $this->createMessages('name', [
            'between' => "Nazwa grupy powinna zawierać od " . $this->between('name.min') . " do " . $this->between('name.max') . " znaków",
        ]);
    }
}
