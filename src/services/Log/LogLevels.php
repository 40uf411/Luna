<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/14/2018
 * Time: 10:24 PM
 */

namespace Luna\services\Log;


abstract class LogLevels
{
    const EMERGENCY = 0;
    const ALERT     = 1;
    const CRITICAL  = 2;
    const ERROR     = 3;
    const WARNING   = 4;
    const NOTICE    = 5;
    const INFO      = 6;
    const DEBUG     = 7;

    const LEVELS = [
        'EMERGENCY', 'ALERT', 'CRITICAL', 'ERROR', 'WARNING', 'NOTICE', 'INFO', 'DEBUG'
    ];
}