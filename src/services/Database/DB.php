<?php

namespace  Luna\services;

use Luna\Core\Service;

use Luna\Andromeda\Andromeda;

class DB extends Service
{
    public static function init($info = null)
    {
        parent::init($info);
    }

    public static function config($info = null)
    {
        parent::config($info);

        Andromeda::config();
    }
}