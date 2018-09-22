<?php

namespace Luna\lib\Factory;


use Luna\services\storage\File;

class Builder
{
    /**
     * @param $what
     * @param $data
     * @throws \Error
     */
    public static function build($what, $data)
    {
        if ( ! File::exist(SOURCE_PATH . "lib" . DS . "Factory" . DS . "templates" . DS . $what . ".php"))
        {
           error("Sorry! our factory doesn't make a product by the name : $what");
        }
        require_once SOURCE_PATH . "lib" . DS . "Factory" . DS . "templates" . DS . $what . ".php";

        $f = new $what;

        if ($f instanceof Template)
            $f->make($what, $data);
    }
}