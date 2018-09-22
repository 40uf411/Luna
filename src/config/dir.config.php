<?php

namespace Luna\Config;

    // Define path constants

    define("DS", DIRECTORY_SEPARATOR);

    define("NL", PHP_EOL);

    define("UP", DS . ".." . DS);


    define("ROOT_URL", "http://127.0.0.1/Luna/");

    define("PUBLIC_URL", ROOT_URL . "public/");


    # /

    if (php_sapi_name() === 'cli')
        define("ROOT", realpath( getcwd() ) . DS);
    else
        define("ROOT", realpath( getcwd() . UP) . DS);

    define("APP_PATH", ROOT . 'app' . DS);

    define("CONFIG_PATH", ROOT . "config" . DS);

    define("PUBLIC_PATH", ROOT . "public" . DS);

    define("SOURCE_PATH", ROOT . "src" . DS);

    define("STORAGE_PATH", ROOT . "storage" . DS);

    define("RESOURCES_PATH", ROOT . "resources" . DS);

    define("TESTS_PATH", ROOT . "public" . DS);


    # /app/

    define("AUTH_PATH", APP_PATH . "auth" . DS);

    define("CONTROLLERS_PATH", APP_PATH . "controllers" . DS);

    define("MIDDLEWARE_PATH", APP_PATH . "middleware" . DS);

    define("MODELS_PATH", APP_PATH . "models" . DS);

    define("VALIDATORS_PATH", APP_PATH . "validators" . DS);


    # /src/

    define("CONFIG_SRC_PATH", SOURCE_PATH . "core" . DS);

    define("CORE_PATH", SOURCE_PATH . "core" . DS);

    define("HELPERS_PATH", SOURCE_PATH . "helpers" . DS);

    define("LIB_PATH", SOURCE_PATH . "lib" . DS);

    define("SERVICES_PATH", SOURCE_PATH . "services" . DS);


    # /storage/

    define("DISKS_PATH", STORAGE_PATH . "disks" . DS);

    define("FRAMEWORK_PATH", STORAGE_PATH . "framework" . DS);

    define("CACHE_PATH", STORAGE_PATH . "framework" . DS . "cache" . DS);

    define("SESSIONS_PATH", STORAGE_PATH . "framework" . DS . "sessions" . DS);

    define("SQL_PATH", STORAGE_PATH . "framework" . DS . "sql" . DS);

    define("LOGS_PATH", STORAGE_PATH . "framework" . DS . "logs" . DS);

    define("TMP_PATH", FRAMEWORK_PATH . "tmp" . DS);


    # /public/

    define("HTML_PATH", PUBLIC_PATH . "assets" . DS .  "html" . DS);

    define("CSS_PATH", PUBLIC_PATH . "assets" . DS .  "css" .DS);

    define("JS_PATH", PUBLIC_PATH . "assets" . DS . "js" . DS);

    define("IMG_PATH", PUBLIC_PATH . "assets" . DS . "img" . DS);

    # /resources/

    define("VIEWS_PATH", RESOURCES_PATH . DS . "views" . DS);

    define("LANG_PATH", RESOURCES_PATH . DS . "lang" . DS);


    # /vendor/

    define("VENDOR_PATH", ROOT . "vendor" . DS);


    # url links

    define("HTML_URL", PUBLIC_URL . "html" . DS);

    define("CSS_URL", PUBLIC_URL .  "css/");

    define("JS_URL", PUBLIC_URL . "js/" );

    define("IMG_URL", PUBLIC_URL . "img/" );

    define("UPLOAD_URL", PUBLIC_URL . "uploads" . DS);



