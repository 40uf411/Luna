<?php

use Luna\Core\View as view;
use Luna\Helpers\Loader;

class WelcomeView extends view
{
    public static function head($data)
    {
        return "
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
                    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
                    <title>" . $data['title'] . "</title>
                    <style>
                            body{
                                background-image: url( '" . Loader::img("img0", "jpg",true ) .  "' );
                            }
                    </style>
                    <link href='". Loader::css('LDF', true) ."' rel='stylesheet' type='text/css'>
                    <link href='". Loader::css('style', true) ."' rel='stylesheet' type='text/css'>
                </head>
            ";
    }

    public static function body($data)
    {
        return "
            <body>
                <div class='background'></div>
                <div class='page'>
                     <p class='title'>welcome " . $data['name'] . " </p> 
                </div>
            </body>
            <script src='" . Loader::js("main",true) . "'></script>";


    }



}