<?php

/**
 *  return a list of console configurations and registered commands
 */
return [
    'configs' => [
        'default_command' => 'luna',
        "allow_colors" => true,
        'command_char_case' => false
    ],

    'commands' => [
        "luna" => [
            "namespace" => '\Luna\services\Cli\commands\Luna_command',
            "location" => SERVICES_PATH . "cli" . DS . "commands" . DS . "Luna_command.php"
        ],
        'factory' => [
            'namespace' => "Luna\lib\Factory\command\Factory",
            "location" => SOURCE_PATH . "lib" . DS . "Factory" . DS . "command" . DS . "Factory.php"
        ]
    ],
];