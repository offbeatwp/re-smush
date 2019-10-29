<?php

namespace OffbeatWP\ReSmush\Helpers;

use OffbeatWP\ReSmush\Helpers\Base\SmushApi;

class SmushImage extends SmushApi
{

    public function __construct($imageType, $imageFile)
    {
        $this->image->type = $imageType;
        $this->image->file = $imageFile;
        $this->url = 'http://api.resmush.it/?qlty=';
        $this->exif = true;
    }

}