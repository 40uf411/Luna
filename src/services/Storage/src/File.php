<?php
namespace Luna\services\storage;

use Luna\services\Timer\Time;

class File
{
    private $connection;
    private $location;
    private $content;
    private $lines;
    private $size;
    private $type;
    private $owner;
    private $group;
    private $perms;
    private $realpath;
    private $basename;
    private $dirname;
    private $last_access;
    private $last_change;
    private $last_modification;

    public function __construct($location = null, $mode = "a+")
    {
        if (! isset($location))
        {

        }
        elseif (  self::exist($location))
        {
            $this->connection = fopen($location, $mode);
            $this->location  = $location;
            $this->content   = file_get_contents($location);
            $this->lines     = file      ($location);
            $this->size      = filesize  ($location);
            $this->type      = filetype  ($location);
            $this->owner     = fileowner ($location);
            $this->group     = filegroup ($location);
            $this->perms     = fileperms ($location);
            $this->realpath  = realpath  ($location);
            $this->basename  = basename  ($location);
            $this->dirname   = dirname   ($location);
            $this->last_access= (new Time())->load(fileatime ($location));
            $this->last_change= (new Time())->load(filectime($location));
            $this->last_modification = (new Time())->load(filemtime ($location));
        }
        else
        {
            $this->location  = $location;
            $this->size = 0;
            file_put_contents($location, '',FILE_APPEND);
        }
    }

    public function load($location, $mode = "a+")
    {
        if (  self::exist($location))
        {
            $this->connection = fopen($location, $mode);
            $this->location  = $location;
            $this->content   = file_get_contents($location);
            $this->lines     = file      ($location);
            $this->size      = filesize  ($location);
            $this->type      = filetype  ($location);
            $this->owner     = fileowner ($location);
            $this->group     = filegroup ($location);
            $this->perms     = fileperms ($location);
            $this->realpath  = realpath  ($location);
            $this->basename  = basename  ($location);
            $this->dirname   = dirname   ($location);
            $this->last_access= (new Time())->load(fileatime ($location));
            $this->last_change= (new Time())->load(filectime($location));
            $this->last_modification = (new Time())->load(filemtime ($location));
        }
        else
        {
            //throw new \Exception();
        }

        return $this;
    }

    public function read($length = null)
    {
        $length = empty($length) ? $this->size + 1 : $length ;

        return fread($this->connection, $length);
    }
    
    public function put($data, $erase = false)
    {
        $this->content = $data;

        $erase = ( ! $erase)? FILE_APPEND : null;

        file_put_contents($this->location, $data, $erase);
    }

    public function csv($fields, $separator = ",", $enclosure = '"')
    {
        fputcsv($this->realpath, $fields, $separator, $enclosure);
    }

    public function seek($offset, $whence = null)
    {
        return fseek($this->realpath,$offset,$whence);

    }

    public function write($string, $length = null)
    {
        return fwrite($this->connection, $string, $length);
    }

    public function copy($newfile)
    {
        return copy($this->realpath, $newfile);
    }

    public function close()
    {
        fclose($this->connection);
    }

    public function location()
    {
        return $this->location;
    }
    public function basename(): ? string
    {
        return $this->basename;
    }
    public function content()
    {
        return $this->content;
    }
    public function dirname(): ? string
    {
        return $this->dirname;
    }
    public function group()
    {
        return $this->group;
    }
    public function getLines()
    {
        return $this->lines;
    }
    public function owner()
    {
        return $this->owner;
    }
    public function type(): ? string
    {
        return $this->type;
    }
    public function size(): ? int
    {
        return $this->size;
    }
    public function perms()
    {
        return $this->perms;
    }
    public function realpath()
    {
        return $this->realpath;
    }
    public function lastChange()
    {
        return $this->last_change;
    }
    public function lastAccess()
    {
        return $this->last_access;
    }
    public function lastModification()
    {
        return $this->last_modification;
    }


    public function setGroup($group)
    {
        chgrp($this->realpath,$group);

        $this->group = $group;

        return $this;
    }

    public function setOwner($owner)
    {
        chown($this->realpath,$owner);

        $this->owner = $owner;

        return $this;
    }

    public function setPerms($perms)
    {
        chmod($this->realpath,$perms);

        $this->perms = $perms;

        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    public function setBasename(string $basename)
    {
        $this->basename = $basename;

        return $this;
    }

    public function rename($name)
    {
        return rename($this->realpath,$this->dirname . DS . $name);
    }

    public function delete()
    {
        $this->close();

        return unlink($this->realpath);
    }

    public static function exist($location)
    {
        return file_exists($location);
    }
    public static function is($location)
    {
        return is_file($location);
    }
    public static function readable($location)
    {
        return is_readable($location);
    }
    public static function writable($location)
    {
        return is_writable($location);
    }
    public static function post_uploaded($location)
    {
        return is_uploaded_file($location);
    }

}