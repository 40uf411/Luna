<?php

return [
    "default" => "json",

    "config" => [

        "JSON" => [
            "db_location" => realpath(FRAMEWORK_PATH . "Andromeda" . DS . "json") . DS,
        ],

        "MYSQL" => [
            "host" => "localhost",
            "name" => "test",
            "user" => "root",
            "pass" => "",
            'charset' => 'utf8',
            'engine' => "",
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC
            ]
        ]
    ],
];