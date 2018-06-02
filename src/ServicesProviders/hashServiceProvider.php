<?php

namespace Luna\services;

use Luna\core\ServiceProvider;
use Luna\ServiceProvider\Files;

class Hash extends ServiceProvider
{
    public static function sha1($data)
    {
        if (Files::exists($data))

            return  sha1_file($data);

        else

            return sha1($data);
    }

    public static function md5($data)
    {
        if (Files::exists($data))

            return  md5_file($data);

        else

            return md5($data);
    }

    public static function password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    public static function password_verify($password, $hash)
    {
        return password_verify($password, $hash);
    }

}