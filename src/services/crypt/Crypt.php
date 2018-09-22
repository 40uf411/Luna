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
}