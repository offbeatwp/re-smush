<?php

namespace OffbeatWP\ReSmush\Helpers\Smushers;

use OffbeatWP\ReSmush\Helpers\Smushers\Base\SmushApi;

class Resmush extends SmushApi
{

    public function __construct($imageType, $imageFile)
    {
        $this->image->type = $imageType;
        $this->image->file = $imageFile;
        $this->url = 'http://api.resmush.it/?qlty=';
        $this->exif = true;
    }

}