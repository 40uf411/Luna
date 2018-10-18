<?php
use Luna\Helpers\Loader;

return [
    Luna\services\Session::class => [
        "location" => "Session" . DS . "Session",
        "cli" => false,
        "init" => true,
        "init_pram" => true,
        "config" => true,
        "config_pram" => Loader::config("services" . DS . "session")
    ],

    Luna\services\ErrorHandler::class => [
        "location" => "Errors_Handler" . DS . "ErrorHandler",
        "cli" => true,
        "init" => true,
        "init_pram" => true,
        "config" => false,
        "config_pram" => []
    ],

    Luna\services\Http\HttpClassesLoader::class => [
        "location" => "Http" . DS . "HttpClassesLoader",
        "cli" => false,
        "init" => false,
        "init_pram" => [],
        "config" => false,
        "config_pram" => []
    ],

    Luna\services\Router::class => [
        "location" => "Router" . DS . "Router",
        "cli" => true,
        "init" => true,
        "init_pram" => [],
        "config" => true,
        "config_pram" => []
    ],

    Luna\services\Cookie::class => [
        "location" => "Cookie" . DS . "Cookie",
        "cli" => false,
        "init" => true,
        "init_pram" => true,
        "config" => true,
        "config_pram" => Loader::config("services" . DS . "cookies")
    ],

    Luna\services\DB::class => [
        "location" => "Database" . DS . "DB",
        "cli" => true,
        "init" => false,
        "init_pram" => true,
        "config" => true,
        "config_pram" => []
    ],

    Luna\services\Storage::class => [
        "location" => "Storage" . DS . "Storage",
        "cli" => true,
        "init" => true,
        "init_pram" => true,
        "config" => true,
        "config_pram" => Loader::config("services" . DS . "storage")
    ],

    Luna\services\Log::class => [
        "location" => "Log" . DS . "Log",
        "cli" => true,
        "init" => true,
        "init_pram" => true,
        "config" => true,
        "config_pram" => Loader::config("services" . DS . "log")
    ],

    Luna\services\View::class => [
        "location" => "View" . DS . "View",
        "cli" => false,
        "init" => true,
        "init_pram" => [],
        "config" => true,
        "config_pram" => Loader::config("services" . DS . "view")
    ],

    Luna\services\Crypt::class => [
        "location" => "Crypt" . DS . "Crypt",
        "cli" => true,
        "init" => false,
        "init_pram" => true,
        "config" => false,
        "config_pram" => []
    ],

    Luna\services\Console::class => [
        "location" => "Cli" . DS . "Console",
        "cli" => true,
        "init" => true,
        "init_pram" => true,
        "config" => true,
        "config_pram" => []
    ],

    Luna\services\Timer::class => [
        "location" => "Timer" . DS . "Timer",
        "cli" => true,
        "init" => false,
        "init_pram" => true,
        "config" => false,
        "config_pram" => []
    ],

    Luna\services\Schedule::class => [
        "location" => "Schedule" . DS . "Schedule",
        "cli" => true,
        "init" => true,
        "init_pram" => true,
        "config" => true,
        "config_pram" => ''
    ],
];