<?php

namespace Luna\lib\Factory;


use Luna\services\Cli\Printer;
use Luna\services\Cli\Progress;
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
        $pr = new Progress();

        $pr->progressBar = false;

        $pr->step("Checking the validation of the product to make.", 20, "ok", "green");

        require_once SOURCE_PATH . "lib" . DS . "Factory" . DS . "templates" . DS . $what . ".php";

        $pr->step("Including the product maker machine.", 40, "ok", "green");

        $f = new $what;

        $pr->step("making a virtual concept .", 60, "ok", "green");

        if ($f instanceof Template)
        {
            $file = $f->make($data);

            $f = _file($file['path']);

            $f->put($file['content'], true);

            $pr->step("Product has been made successfully.", 100, "ok", "green");

            (new Printer())->print(NL .NL ." Product location is:");
            (new Printer())->print( $file['path'], "blue");
        }
        else
        {
            $pr->step("Error! Product was not made, we have an issue in the production line.", 100, "Fail", "red");
        }

    }
}