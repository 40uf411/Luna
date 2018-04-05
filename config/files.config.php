<?php
use Luna\Providers\FilesProvider as Files;

Files::config(
    [
        'DEFAULT_PREFIX' => null,

        'DEFAULT_UPLOAD_PATH' => null,

        'DEFAULT_MIN_SIZE' => null,

        'DEFAULT_MAX_SIZE' => null,

        'DEFAULT_ALLOWED_TYPES' => ['image/jpeg', 'image/jpg', 'image/png','image/gif', 'text/plain'],

        'DEFAULT_ALLOWED_EXTENSIONS' => ['jpg', 'jpeg', 'gif', 'png', 'zip', 'xlsx', 'cad', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', '.mp3', 'm4a', 'ogg', 'wav', 'mp4', 'm4v', 'mov', 'wmv', 'css', 'txt'],

        'DEFAULT_DB_DETAILS_SAVE' => null,

        'DEFAULT_FILE_DETAILS_SAVE' => null,

    ],
    [
        'table_column' => 'files',

        'id_column' => 'id',

        'name_column' => 'name',

        'type_column' => 'type',

        'extension_column' => 'extension',

        'upload_at_column' => 'upload_at',

        'update_at_column' => 'update_at',

        'download_times_column' => 'download_times',

    ]
);