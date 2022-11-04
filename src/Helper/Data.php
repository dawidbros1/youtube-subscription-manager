<?php

declare (strict_types = 1);

namespace App\Helper;

class Data
{
    public static function time_elapsed_string($datetime)
    {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);

        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (count($string) > 0) {
            return (array_shift($string) . " ago");
        } else {
            return "just now";
        }
    }
}
