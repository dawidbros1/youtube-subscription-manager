<?php

namespace App\Component\Form;

class Select
{
    public $require = ['label', 'name', 'options', 'show', 'value', 'selected'];
    public $template = "components/form/select.php";
}
