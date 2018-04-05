<?php
use Luna\Helpers\Loader;
use Luna\Providers\FilesProvider;



//echo "<pre>".print_r(FilesProvider::$files_config,true)."</pre>";

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome</title>
    <link href="<?php Loader::css('style') ?>" rel="stylesheet" type="text/css">
</head>
<body>
    <h1>welcome <?php echo $data ?></h1>
    <form action="http://127.0.0.1/new/home/file" method="post" enctype="multipart/form-data">
        <input name="file" type="file">
        <input name="file1" type="file">
        <input type="submit">
    </form>
</body>
</html>
















