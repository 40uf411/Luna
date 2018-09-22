<?php


function LunaErrorHandler($errno, $errstr, $errfile, $errline)
{
    \Luna\services\ErrorHandler::Error_handler($errno, $errstr, $errfile, $errline);
}
// set to the user defined error handler
//set_error_handler("LunaErrorHandler");

function LunaExceptionHandler($exception)
{
    \Luna\services\ErrorHandler::Exception_handler($exception);
}
//set_exception_handler('LunaExceptionHandler');

/*
register_shutdown_function(function() {
    $errorData = error_get_last();
    if (is_array($errorData)) {
        ob_end_clean();
        echo 'Error occured! - ' . $errorData['message'];
    }
});*/

// registring the whoops error handler;

$whoops = new \Whoops\Run;

if (php_sapi_name() === 'cli')
{

    $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
}
else
{
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
}
$whoops->register();

