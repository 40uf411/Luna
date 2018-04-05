<?php
    // allowing open files by url

    ini_set("allow_url_fopen", 1);

    // changing the default upload size

    ini_set('post_max_size', '64M');

    // changing the default upload size

    ini_set('upload_max_filesize', '64M');

    //changing the default max execution time to 5 minutes

    ini_set('max_execution_time', 3600);

    //changing the default memory limit

    ini_set('memory_limit', '512M');
