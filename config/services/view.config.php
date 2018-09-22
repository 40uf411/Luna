<?php

return [
    'default' => 'twig',

    'packages'=> [

        'twig' => [
            'cache' => CACHE_PATH . DS . 'twig',
            'debug' => true,
            'charset' => 'utf-8',
            'auto_reload' => true,
            'strict_variables' => true,
            'optimizations' => -1,
            "sub_domain" => "twig",
            'extension' => ".twig"
        ],

        'smarty' => [
            "debugging" => true,
            "caching" => true,
            "cache_lifetime" => 120,

            "compile_dir" => CACHE_PATH . DS . 'smarty' . DS . "compile",
            "config_dir" => CONFIG_PATH . "lib" . DS . "smarty" ,
            "cache_dir" => CACHE_PATH . DS . 'smarty' . DS . "cache",
            "sub_domain" => "smarty",
            'extension' => ".tpl",
        ],

        'plates' => [
            "sub_domain" => "plates",
            'extension' => ".php",

        ]

    ]
];