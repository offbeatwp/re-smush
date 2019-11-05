<?php

namespace OffbeatWP\ReSmush\Helpers\Smushers;

use OffbeatWP\ReSmush\Helpers\General;
use OffbeatWP\ReSmush\Helpers\Smushers\Base\SmushApi;

class Resmush extends SmushApi
{
    public function __construct()
    {
        $this->url = 'http://api.resmush.it/?qlty=';

        $this->exif = true;
    }

}