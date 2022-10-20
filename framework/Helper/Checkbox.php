<?php

declare (strict_types = 1);

namespace Phantom\Helper;

class CheckBox
{
    # Method returns 1 if checkbox exist and else 0
    public static function get($checkbox)
    {
        if ($checkbox) {return 1;} else {return 0;}
    }
}
