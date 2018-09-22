<?php

namespace Luna\services;

use Luna\Andromeda\Andromeda;
use Luna\Core\Service;
use Luna\services\Log\LogLevels;


class Log extends Service
{

    private static $config;

    private static $init = false;

    private static $self;

    private $db;

    public static function config($info = null)
    {
        parent::config($info);

        self::$config = $info;
    }

    public static function init($info = null)
    {
        require_once "LogLevels.php";
    }

    public static function instance()
    {
        if (self::$init)
        {
            return self::$self;
        }
        else
        {
            self::$self = new self();
            self::$init = true;
            return self::$self;
        }
    }

    private  function __construct()
    {
        if (self::$config['db_log'])
        {
            $this->db = Andromeda::connect([
                "name"=> "Luna_db",
                "user"=> "root",
                "pass"=> "admin0147"
            ]);
        }
    }

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency   ($message, array $context = [])
    {
        $this->log(LogLevels::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert       ($message, array $context = [])
    {
        $this->log(LogLevels::EMERGENCY, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical    ($message, array $context = [])
    {
        $this->log(LogLevels::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error       ($message, array $context = [])
    {
        $this->log(LogLevels::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning     ($message, array $context = [])
    {
        $this->log(LogLevels::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice      ($message, array $context = [])
    {
        $this->log(LogLevels::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info        ($message, array $context = [])
    {
        $this->log(LogLevels::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug       ($message, array $context = [])
    {
        $this->log(LogLevels::DEBUG, $message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log         ($level, $message, array $context = [])
    {
        if ( isset(LogLevels::LEVELS[strtoupper(self::$config["log_level"])]) and $level < LogLevels::LEVELS[strtoupper(self::$config["log_level"])])
        {
            $t = now();

            if (self::$config['db_log'])
            {
                $this->db
                    ->insert([
                        "time"=> "$t",
                        "level" => $level,
                        "message" => $message,
                        "context" => $context
                    ])
                    ->inTo("log")
                    ->exec();
            }

            if (self::$config['file_log'])
            {
                $s = "[ $t ] " . strtoupper(LogLevels::LEVELS[$level]) . "! $message | " . arrayToString($context, '$k: $v') . NL;

                $f = _file(self::$config['default_log_folder'] . LogLevels::LEVELS[$level] . ".txt")->put($s);

                echo self::$config['default_log_folder'] . LogLevels::LEVELS[$level] . ".txt";
            }

            if (self::$config['cli_print'] and is_cli())
            {
                echo "[ $t ] " . strtoupper(LogLevels::LEVELS[$level]) . "! $message | " . arrayToString($context, '$k: $v') . NL;

            }

            if (self::$config['web_print'] and ! is_cli())
            {
                echo "[ $t ] " . strtoupper(LogLevels::LEVELS[$level]) . "! $message | " . arrayToString($context, '$k: $v') . "<br>";

            }
        }
    }
}