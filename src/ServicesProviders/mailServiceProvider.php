<?php

namespace Luna\ServiceProvider;

use Luna\Core\ServiceProvider;
use Luna\ServiceProvider\Logger as Log;
use Luna\validators\inputValidator;

class Mail extends ServiceProvider
{
    public static function send( $to , $subject , $message )
    {
        if (inputValidator::email($to)){

            mail($to, $subject,$message);

            Log::save("email", "a new email has been sent to: [ $to ] subject: [ $subject ] message: [ $message ]", "Success");

            return true;
        }

        Log::save("email", "a new email has been sent to: [ $to ] subject: [ $subject ] message: [ $message ]", "Fail");

        return false;
    }
}