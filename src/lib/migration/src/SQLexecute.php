<?php

namespace Luna\lib\migration\src;

use Luna\ServiceProvider\Files;
use Luna\ServiceProvider\Databases;

class SQLexecute
{
    public static function execute($sql)
    {
        $db =  new Databases();

        if (Files::exists($sql))

            return $db->query( Files::load($sql) );

        else

            return $db->query($sql);

    }
}