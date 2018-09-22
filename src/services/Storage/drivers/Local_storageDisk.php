<?php

use Luna\Services\Storage\src\Disk;

class Local_storageDisk extends Disk
{

    /**
     * @var JSON_AD
     */
    private $db;

    private $table;

    private $files;

    public function connect($db)
    {
        $this->db = \Luna\Andromeda\Andromeda::connect([
            "name" => $db['db_name'],
            "user" => $db['db_user'],
            "pass" => $db['db_pass']
        ]);

        $this->table = $db['table'];
    }

    public function load()
    {
        $this->files = $this->db->select()->from($this->table)->fetchAll();
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function url($key)
    {
        if ($this->exist($key))
            return APP_URL . "files/" . $this->config['url'] . '/' . $key;
        else
            return false;
    }

    /**
     * @param $file
     * @param null $config
     * @return string
     * @throws Exception
     */
    public function upload($file, $config = null)
    {
        dump($config);

        $n = explode(".",$file['name']);

        $file['extension'] = (count($n) > 1) ? $n[count($n) - 1] : null;

        $file['name'] =(isset($config['name'])) ? $config['name'] : substr($file['name'],0,-strlen($file['extension'])-1);

        $file["sub_folder"] = (isset($config['sub_folder'])) ? $config['sub_folder'] : DS;

        $file["visibility"] = (isset($config['visibility'])) ? $config['visibility'] : 0;

        $id = (isset($config['id'])) ? $config['id'] : $file["sub_folder"] . DS . $file['name'] . "." .$file['extension'];

        $t = $this->testF($file);
        if ( $t === "ok" and ! $this->db->from($this->table)->exist($id) )
        {
            _dir($this->config['folder'] . DS , $file["sub_folder"] . DS, false);

            if (move_uploaded_file($file['tmp_name'], $this->config['folder'] . DS . $file["sub_folder"] . DS . $file['name'] . "." .$file['extension'] ) )
            {
                unset($file["tmp_name"]);
                unset($file["error"]);
                unset($file["id"]);
                $this->db->insert($id, $file)->inTo($this->table)->exec();
            }
            return "ok";
        }
        else
        {
            if ($t !== "ok")
            {
                return $t;
            }
            else
            {
                return "a file with the same id already exist";
            }
        }
    }

    /**
     * @param $key
     * @throws Error
     * @throws Exception
     */
    public function download($key)
    {
        $r = \Luna\services\Http\Response::instance();

        if ($this->exist($key['id']))
        {
            $f = $this->get($key['id']);

            $r->header("Pragma","public");
            $r->header("Expires","0");
            $r->header("Cache-Control","must-revalidate, post-check=0, pre-check=0");
            $r->header("Content-Type",$f['type']);
            $r->header("Content-Disposition",'attachment; filename="' . $f['name'] . '.' . $f['extension'] . '";');
            $r->header("Content-Transfer-Encoding","binary");
            $r->header("Content-Length",$f['size']);

            readfile($f['real_path']);
        }
        else
            $r->status(404);
    }

    /**
     * @param $key
     * @throws Exception
     */
    public function remove($key)
    {
        dump(
            $this->db->from($this->table)->get($key)
        );

        $f = $this->db->from($this->table)->get($key);

        if (\Luna\services\storage\File::exist($this->config['folder'] . DS . $f['sub_folder'] . DS . $f['name'] . "." . $f['extension']))
            _file( $this->config['folder'] . DS . $f['sub_folder'] . DS . $f['name'] . "." . $f['extension'] )->delete();


        if ($this->db->from($this->table)->remove($key))
            $this->db->from($this->table)->remove($key);
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public function exist($key)
    {
        if($this->db->from($this->table)->exist($key))
        {
            $f =  $this->db->from($this->table)->get($key);

            if ($f['visibility'] == 1 or ($f['visibility'] == 0 and $this->config['visibility'] == 1) )
            {
                return true;
            }
            else
                return false;
        }
    }

    /**
     * @param $key
     * @return array
     * @throws Exception
     */
    public function get($key)
    {
        if($this->db->from($this->table)->exist($key))
        {
            $f =  $this->db->from($this->table)->get($key);

            if ($f['visibility'] == 1 or ($f['visibility'] == 0 and $this->config['visibility'] == 1) )
            {
                return array_merge($f,["real_path" => $this->config['folder'] . DS . $f["sub_folder"] . DS . $f['name'] . "." . $f['extension']]);
            }
        }
    }

    /**
     * @param $var
     * @return JSON_AD|null
     * @throws Exception
     */
    public function __get($var)
    {
       switch ($var)
       {
           case "db":
           case "databese":
               $d = clone $this->db;
               return $d->lock()->from($this->table);
               break;
           default:
               return null;
               break;
       }
    }
}