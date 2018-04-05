<?php

namespace Luna\Providers;

use Luna\Providers\Log;
use Luna\Providers\HttpProvider as http;
use Luna\Providers\DatabaseProvider as DatabaseProvider;

class FilesProvider
{
    private static $db;

    public static $files_config = [
        'DEFAULT_PREFIX' => null,
        'DEFAULT_UPLOAD_PATH' => null,
        'DEFAULT_MIN_SIZE' => null,
        'DEFAULT_MAX_SIZE' => null,
        'DEFAULT_ALLOWED_TYPES' => ['image/jpeg', 'image/jpg', 'image/png','image/gif', 'TEXT'],
        'DEFAULT_ALLOWED_EXTENSIONS' => ['jpeg', 'jpg', 'png', 'gif', 'TXT'],
        'DEFAULT_DB_DETAILS_SAVE' => null,
        'DEFAULT_FILE_DETAILS_SAVE' => null,
    ];
    private static $db_config = [
        'table_name' => null,
        'id_column' => null,
        'name_column' => null,
        'type_column' => null,
        'extension_column' => null,
        'upload_at_column' => null,
        'update_at_column' => null,
        'download_times_column' => null,
    ];

    public static function config( array $file_config, array $db_config)
    {
        self::$db = new DatabaseProvider();

        self::$files_config = $file_config;

        self::$db_config = $db_config;
    }
    private static function insert_db(array $file)
    {
        self::$db->query(" INSERT INTO " . self::$db_config['table_name'] . " (" . self::$db_config['name_column'] . ", " . self::$db_config['type_column'] . ", " . self::$db_config['extension_column'] . ") VALUES (" . $file['name'] . $file['type'] . $file['extension']  . ") ");
    }
    private static function inc_downloads($file)
    {
        $dt = self::$db->getData(false, self::$db_config['download_times_column'], self::$db_config['table_name'], " WHERE " . self::$db_config['name_column'] . " = " . $file ) + 1;

        self::$db->query("UPDATE " . self::$db_config['table_name'] . " SET " . self::$db_config['download_times_column'] . " = $dt WHERE " .self::$db_config['table_name'] . self::$db_config['name_column']  = $file );
    }

    /**
     * @param $file
     * @param $name
     * @param $type
     */
    public static function download($file, $name = null, $type)
    {
            if (self::$files_config['DEFAULT_DB_DETAILS_SAVE'])

                self::inc_downloads($name);


            Log::save('download',"[ $file ] new download has been launched by [ ". http::get_client_ip_address()." ] ", "Success");

            header('Pragma: public');

            header('Expires: 0');

            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            header('Cache-Control: private', false); // required for certain browsers

            header('Content-Type: ' . $type);


            header('Content-Disposition: attachment; filename="'. basename($file) . '";');

            header('Content-Transfer-Encoding: binary');

            header('Content-Length: ' . filesize($file));


            readfile($file);
    }

    /**
     * @param $file_field
     * @param bool $check_image
     * @param bool $random_name
     * @return array
     */
    public static function upload($file_field , $check_image = false, $random_name = false) {

        //The Validation
        // Create an array to hold any output

        $out = [ 'errors' => [] ];

        if (!$file_field) {
            $out['errors'][] = "Please specify a valid form field name";
        }

        if (count($out['errors'])>0) {
            return $out;
        }

        //Make sure that there is a file

        if( isset($_FILES[$file_field]) && ($_FILES[$file_field]['error'] == 0))
        {

            // Get filename
            $file_info = pathinfo($_FILES[$file_field]['name']);

            $name = $file_info['filename'];

            $ext = $file_info['extension'];

            //Check file has the right extension

            if (!in_array($ext, self::$files_config['DEFAULT_ALLOWED_EXTENSIONS']))
            {
                $out['errors'][] = "Invalid file Extension";
            }

            //Check that the file is of the right type

            if (!in_array($_FILES[$file_field]["type"], self::$files_config['DEFAULT_ALLOWED_TYPES']))
            {
                $out['errors'][] = "Invalid file Type";
            }

            //Check that the file is not too big
/*
            if ($_FILES[$file_field]["size"] > self::$files_config['DEFAULT_MAX_SIZE'])
            {
                $out['errors'][] = "File is too big";
            }
*/
            //If $check image is set as true

            if ($check_image)
            {
                if (!getimagesize($_FILES[$file_field]['tmp_name']))
                {
                    $out['errors'][] = "Uploaded file is not a valid image";
                }
            }

            //Create full filename including path
            if ($random_name)
            {
                // Generate random filename
                $tmp = str_replace(array('.',' '), array('',''), microtime());

                if (!$tmp || $tmp == '')
                {
                    $out['errors'][] = "File must have a name";
                }
                $name = self::$files_config['DEFAULT_PREFIX'] . rand(1000,9999) . "_" . $tmp ;

                $newname = self::$files_config['DEFAULT_PREFIX'] . rand(1000,9999) . "_" . $tmp . '.' . $ext;
            }
            else
            {
                $name = self::$files_config['DEFAULT_PREFIX'] . rand(1000,9999) . "_" . $name;

                $newname = self::$files_config['DEFAULT_PREFIX'] . rand(1000,9999) . "_" . $name . '.' . $ext;
            }


            //Check if file already exists on server
            if (file_exists(self::$files_config['DEFAULT_UPLOAD_PATH'].$newname))
            {
                $out['errors'][] = "A file with this name already exists";
            }


            if (count($out['errors'])>0)
            {
                //The file has not correctly validated
                return $out;
            }

            if (move_uploaded_file($_FILES[$file_field]['tmp_name'], UPLOAD_PATH . $newname))
            {

                //Success
                $out['file_path'] = self::$files_config['DEFAULT_UPLOAD_PATH'];

                $out['file_full_name'] = $newname;

                $out['file_name'] = $newname;

                $out['file_type'] = $_FILES[$file_field]["type"];

                if (self::$files_config['DEFAULT_DB_DETAILS_SAVE'])

                    self::insert_db([
                        'name' => $newname,
                        'type' => $_FILES[$file_field]["type"],
                        'extension' => $ext,
                    ]);


                return $out;
            }
            else
            {
                $out['errors'][] = "Server errors!";
            }

        }
        else
        {
            $out['errors'][] = "No file uploaded";
            return $out;
        }
    }




}