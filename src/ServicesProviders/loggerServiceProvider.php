<?php

namespace Luna\ServiceProvider;

use Luna\Core\ServiceProvider;
use Luna\Helpers\Loader;

class Logger extends ServiceProvider
{
    /**
     * @var array  ===> the logs configurations
     */
    private static  $config = [

      'mode' => 'whitelist',

      'whitelist' => [],

      'blacklist' => []

    ];

    public static function config()
    {
        $data = Loader::config("providers" . DS .'log');
        self::$config = $data;
    }

    /**
     * function to save data in to files
     * @param $file
     * @param $data
     * @param null $state
     */
    public static function save($file, $data, $state = null)
    {
         $mode = self::$config['mode'];

         $logs = self::$config[$mode];

         if ($mode == 'whitelist')
         {
             if (array_key_exists($file, $logs))
             {
                 self::log_in($logs[$file] , $data, $state );
             }
         }
         else
         {
             if ( ! array_key_exists($file, $logs))
             {
                 self::log_in($file, $data, $state );
             }
         }
    }

    private static function log_in($file, $data, $state = null)
    {
        $date =  "  [". date("Y-m-d | H:i:s") ."]";

        $f = /* " [ " . __FILE__ . "] " */ null;

        $state = ( $state == null ) ? null : " [ ". $state ." ] ";

        file_put_contents(LOGS_PATH . "$file.log.txt", $date . $state . $f . $data . PHP_EOL,FILE_APPEND);
    }
}