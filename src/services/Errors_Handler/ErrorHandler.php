<?php


namespace Luna\services;

use Luna\Core\Service;

require 'setup.php';

class ErrorHandler extends Service
{
    private static $errors = [];

    public static function init($info = null)
    {
        require_once "errors/Luna_Error.php";
        \Luna_Error::load_errors();
    }
    public static function config($info = null){}

    public static function add( $error, $stop = false)
    {
        self::$errors[] = $error;

        if ($stop)
            self::run();
    }
    public static function run()
    {
        if (! empty(self::$errors))
        {
            ob_clean();
            echo "<pre>";
            var_dump(self::$errors);
            echo "<pre>";
            die();
        }
    }

    public static function Error_handler(...$data)
    {
        //dump($data);
        $errno = $data[0];
        $errstr = $data[1];
        $errfile = $data[2];
        $errline = $data[3];
        if (!(error_reporting() & $errno)) {
            // This error code is not included in error_reporting, so let it fall
            // through to the standard PHP error handler
            return true;
        }

        echo '<br><hr>';
        switch ($errno)
        {
            case E_ERROR:
                echo "<b>Warning! Fatal run-time error [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_RECOVERABLE_ERROR:
                echo "<b>Warning! Catchable fatal error [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_WARNING:
                echo "<b>Warning! Run-time warning (non-fatal errors) [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_PARSE:
                echo "<b>Warning! Compile-time parse error [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_NOTICE:
                echo "<b>Notice! Run-time notices [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_STRICT:
                echo "<b>Notice! Enable to have PHP suggest change [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_DEPRECATED:
                echo "<b>Notice! Run-time notice [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_CORE_ERROR:
                echo "<b>Notice! Fatal errors that occur during PHP's initial startup [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_CORE_WARNING:
                echo "<b>Notice! Warnings (non-fatal errors) that occur during PHP's initial startup [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_COMPILE_ERROR:
                echo "<b>Fatal compile-time errors [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_COMPILE_WARNING:
                echo "<b>Compile-time warnings (non-fatal errors) [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_USER_ERROR:
                echo "<b>User-generated error message [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_USER_WARNING:
                echo "<b>User-generated warning message [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_USER_NOTICE:
                echo "<b>User-generated notice message [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;

            case E_USER_DEPRECATED:
                echo "<b>User-generated warning message [$errno]:</b> $errstr <br>";
                echo "<b>in file :</b> $errfile,<br><b>line :</b> $errline";
                break;
        }
        echo '<br><hr><br>';

        /* Don't execute PHP internal error handler */
        return true;
    }

    public static function Exception_handler( $exeption)
    {
        echo "<hr>";
        if ($exeption instanceof \Exception)
        {
            echo "<br><b>Exception threw [ code : " . $exeption->getCode() . "]</b>";
            echo "<br><b>Line : </b>" . $exeption->getLine();
            echo "<br><b>File : </b>" . $exeption->getFile();
            echo "<br><b>Message : </b>" . $exeption->getMessage();
            echo "<br><b>Previous : </b>" . $exeption->getPrevious();
            echo "<br><b>Trace</b>";
            dump(
                $exeption->getTrace()
            );
        }
        elseif ($exeption instanceof \Error)
        {
            echo "<br><b>Exception threw [ code : " . $exeption->getCode() . "]</b>";
            echo "<br><b>Line : </b>" . $exeption->getLine();
            echo "<br><b>File : </b>" . $exeption->getFile();
            echo "<br><b>Message : </b>" . $exeption->getMessage();
            echo "<br><b>Previous : </b>" . $exeption->getPrevious();
            echo "<br><b>Trace</b>";
            dump(
                $exeption->getTrace()
            );
        }
        echo "<hr>";

    }
}