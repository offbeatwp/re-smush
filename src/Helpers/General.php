<?php

namespace OffbeatWP\ReSmush\Helpers;

use \Exception;

class General
{

    public static function hasAllowedType($image)
    {
        switch ($image) {
            case "image/jpeg":
                return true;
                break;
            case "image/png":
                return true;
                break;
            case "image/gif":
                return true;
                break;
            default:
                return false;
                break;
        }
    }

    public static function hasAllowedSize($image)
    {
        if (filesize($image) < 5242880) {
            return true;
        }

        return false;
    }

}


