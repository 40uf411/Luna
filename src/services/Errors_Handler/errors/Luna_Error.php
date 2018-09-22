<?php

class Luna_Error  // implements Throwable
{
    public function getCode(){}
    public function getFile(){}
    public function getLine(){}
    public function getMessage(){}
    public function getPrevious(){}
    //public function __toString(){}
    public function getTrace(){}
    public function getTraceAsString(){}

    public static function load_errors()
    {
        $errors = require_once "errors.php";

        foreach ($errors as $error)
        {
                require_once $error . '.php';
        }
    }
}