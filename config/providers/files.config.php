<?php

return [
    [
        'DEFAULT_PREFIX' => 'Luna_',

        'DEFAULT_UPLOAD_PATH' => null,

        'DEFAULT_MIN_SIZE' => 10,

        'DEFAULT_MAX_SIZE' => 0,

        'DEFAULT_ALLOWED_TYPES' => [
            'image/jpeg',           'image/jpg',                    'image/png',
            'video/mp4',            'video/flv',                    'video/mpg',
            'image/gif',            'text/plain',                   'text/css',
            'text/html',            'application/javascript',       'application/x-zip-compressed',
            'application/pdf',      'application/octet-stream',
        ],

        'DEFAULT_ALLOWED_EXTENSIONS' => [
            'jpg',  'jpeg',   'gif',    'png',
            'zip',  'xlsx',   'cad',    'pdf',
            'doc',  'docx',   'ppt',    'pptx',
            'pps',  'ppsx',   'odt',    'xls',
            'xlsx', '.mp3',   'm4a',    'ogg',
            'wav',  'mp4',    'm4v',    'mov',
            'wmv',  'css',    'txt',    'js',
            'html', 'zip',    'mp4',    'flv',
            'mpg',  'pdf',    'doc',    'docx',
        ],

        'DEFAULT_DB_DETAILS_SAVE' => false,

        'DEFAULT_FILE_DETAILS_SAVE' => false,

    ],
    [
        // Database information

        'table_column' => 'files',

        'id_column' => 'id',

        'name_column' => 'name',

        'type_column' => 'type',

        'extension_column' => 'extension',

        'upload_at_column' => 'upload_at',

        'update_at_column' => 'update_at',

        'download_times_column' => 'download_times',

    ]
];