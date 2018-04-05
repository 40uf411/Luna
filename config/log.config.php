<?php

use Luna\Providers\Log;

Log::config([
    'mode' => 'whitelist',

    'whitelist' => [
        //'download' =>'download',

        //'database' => "database",

        //'session' => 'session',

        //'cookies' => 'cookies',

        //'models.users' => 'model.users',
    ],

    'blacklist' => [
        //'cookies'
    ]

]);