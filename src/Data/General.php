<?php

namespace OffbeatWP\ReSmush\Data;

class General
{

    public static function imageQualities()
    {
        $qualities = [];

        foreach (range(1, 100) as $number) {
            $qualities[$number] = $number . '%';
        }

        return array_reverse($qualities);
    }

}