<?php

namespace Luna\services;


class Crypt
{
    public static function encryptCookie($value, $key)
    {
        if(!$value){return false;}

        return openssl_encrypt($value,"AES-128-CBC",$key,OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16));
    }

    public static function decryptCookie($value, $key)
    {
        return openssl_decrypt($value, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, openssl_random_pseudo_bytes(16));
    }

    public static function password($password, $algo = PASSWORD_DEFAULT, $options = null)
    {
        return password_hash($password, $algo, $options);
    }

    public static function password_verify($pass, $hash)
    {
        return password_verify($pass, $hash);
    }

    public static function password_rehash($hash, $algo = PASSWORD_DEFAULT, $options = null)
    {
        return password_needs_rehash($hash, $algo, $options);
    }
}