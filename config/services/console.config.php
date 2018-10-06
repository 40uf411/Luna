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
            "location" => SERVICES_PATH . "cli" . DS . "commands" . DS . "Luna_command.php",
            "description" => "Luna default command."
        ],
        'factory' => [
            'namespace' => "Luna\lib\Factory\command\Factory",
            "location" => SOURCE_PATH . "lib" . DS . "Factory" . DS . "command" . DS . "Factory.php",
            "description" => "Factory to make classes."

        ],
        'time' => [
            "namespace" => "Luna\services\Timer\command\\time",
            "location" => SERVICES_PATH . "Timer" . DS . "command" . DS . "time.php",
            "description" => "show the time."

        ],
        'date' => [
            "namespace" => "Luna\services\Timer\command\date",
            "location" => SERVICES_PATH . "Timer" . DS . "command" . DS . "date.php",
            "description" => "show the date."
        ],
    ],
];