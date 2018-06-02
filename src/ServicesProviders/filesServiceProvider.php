<?php

namespace Luna\ServiceProvider;

use Luna\Core\ServiceProvider;
use Luna\Helpers\Loader;
use Luna\ServiceProviders\HTTP;
use Luna\services\Hash;

class Files extends ServiceProvider
{
    private static $db;

    public static $files_config = [];
    private static $db_config = [];

    public static function config()
    {
        self::$db = new Databases();

        $conf  = Loader::config("providers" . DS ."files");

        self::$files_config = $conf[0];

        self::$db_config = $conf[1];
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

            ob_clean();

            Logger::save('download',"[ $file ] new download has been launched by [ ". HTTP::get_client_ip_address()." ] ", "Success");

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

            //Check file has an allowed extension

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

            if ($_FILES[$file_field]["size"] > self::$files_config['DEFAULT_MAX_SIZE'] && self::$files_config['DEFAULT_MAX_SIZE'] != 0)
            {
                $out['errors'][] = "File is too big";
            }

            //Check that the file is not too big

            if ($_FILES[$file_field]["size"] < self::$files_config['DEFAULT_MIN_SIZE'] && self::$files_config['DEFAULT_MIN_SIZE'] != 0)
            {
                $out['errors'][] = "File is too small";
            }
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

                $newname = self::$files_config['DEFAULT_PREFIX'] . rand(1000,9999) . "_" . $tmp . '.' . $ext;
            }
            else
            {

                $newname = self::$files_config['DEFAULT_PREFIX'] . $name . '.' . $ext;
            }
            if (Files::exists(UPLOAD_PATH . $newname))

                $out['errors'][] = "File name already exists";


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

    public static function verify($dir,$file = null)
    {
        return is_file($dir.$file);
    }
    public static function exists($dir,$file = null)
    {
        return file_exists($dir . $file);
    }

    public static function size($dir,$file = null)
    {
        return filesize($dir . $file);
    }

    public static function type($dir,$file = null)
    {
        return filetype($dir . $file);
    }

    public static function owner($dir,$file = null)
    {
        return fileowner($dir . $file);
    }

    public static function group($dir,$file = null)
    {
        return filegroup($dir . $file);
    }

    public static function encrypt($encrypt_type, $dir, $file = null)
    {
        if ($encrypt_type === "sha1")

            Hash::sha1($dir . $file);

        elseif ($encrypt_type === "md5")

            return Hash::md5($dir . $file);

        else

            return FAILURE;
    }

    public static function load($dir,$file = null)
    {
        if (self::exists($dir,$file))
            return file_get_contents($dir.$file);

        return FAILURE;
    }
}