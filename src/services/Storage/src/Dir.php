<?php

namespace Luna\services\Storage;


use Luna\services\Timer\Time;

class dir
{
    private $path;
    private $name;
    private $description;
    private $creation_time;
    private $attributes = [];


    public function __construct($path, $dirname = null, $erase = true)
    {
        if ( ! File::exist($path . $dirname))

            mkdir($path . $dirname);

        $this->path = realpath($path .$dirname) . DS;

        $this->name = $dirname;

        $this->creation_time = (new Time())->now();

        if ($erase || ! File::exist($this->path . DS . "._DI_"))
        {

            $f = new File($this->path . DS . "._DI_");

        }
        else
        {
            $f = (new File)->load($this->path . DS . "._DI_");


            $atts = $f->getLines() ? $f->getLines() : [];

            foreach ($atts as $att => $value)
            {
                $at = explode("::",$value);

                if (! empty($at) and isset($at[1]))
                {

                    $at[1] = str_replace(NL, "", $at[1]);

                    if (in_array($at[0],[ 'path', 'name', 'description', 'creation_time' ]))
                    {
                        switch ($at[0])
                        {
                            case 'path':
                                $this->path = $at[1];
                                break;
                            case 'name':
                                $this->name = $at[1];
                                break;
                            case 'description':
                                $this->description = $at[1];
                                break;
                            case 'creation_time':
                                $this->creation_time = $at[1];
                                break;
                        }
                    }
                    else
                    {
                        $this->attributes[$at[0]] = $at[1];
                    }
                }
            }
        }
        $string = "path::$this->path" . NL . "name::$this->name" . NL . "description::$this->description" . NL . "creation_time::$this->creation_time" . NL;

        foreach ($this->attributes as $attribute => $value)
        {
            $string = $string . "$attribute::$value" .NL;
        }

        $f->put($string, true);

        return $this;
    }

    public function name($name)
    {

        $f = new File($this->path . DS . "._DI_");

        $this->name = $name;

        $string = "path::$this->path" . NL . "name::$this->name" . NL . "description::$this->description" . NL . "creation_time::$this->creation_time" . NL;

        foreach ($this->attributes as $attribute => $value)
        {
            $string = $string . "$attribute::$value" .NL;
        }

        $f->put($string, true);

        return $this;
    }

    public function description($description)
    {
        $f = new File($this->path . DS . "._DI_");

        $this->description = $description;

        $string = "path::$this->path" . NL . "name::$this->name" . NL . "description::$this->description" . NL . "creation_time::$this->creation_time" . NL;

        foreach ($this->attributes as $attribute => $value)
        {
            $string = $string . "$attribute::$value" .NL;
        }

        $f->put($string, true);

        return $this;
    }

    public function attribute($keys, $value = null)
    {
        $f = new File($this->path . DS . "._DI_");

        $string = "path::$this->path" . NL . "name::$this->name" . NL . "description::$this->description" . NL . "creation_time::$this->creation_time" . NL;

        foreach ($this->attributes as $attribute => $value)
        {
            $string = $string . "$attribute::$value" .NL;
        }

        if (is_array($keys))
        {
            $this->attributes = array_merge($this->attributes, $keys);

            foreach ($keys as $key => $val)
            {
                $string = $string . "$key::$val" .NL;
            }
        }
        else
        {
            $this->attributes = array_merge($this->attributes, [$keys => $value]);

            $string = $string . "$keys::$value" .NL;
        }
        $f->put($string , true);

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getCreationTime()
    {
        return $this->creation_time;
    }

    public function rename($new_name)
    {
        rename($this->path , $this->path . UP . $new_name . DS);

        $this->name = $new_name;

        $this->path = realpath($this->path . UP . $new_name) . DS;

        $f = new File($this->path . DS . "._DI_");

        $string = "path::$this->path" . NL . "name::$this->name" . NL . "description::$this->description" . NL . "creation_time::$this->creation_time" . NL;

        foreach ($this->attributes as $attribute => $value)
        {
            $string = $string . "$attribute::$value" .NL;
        }

        $f->put($string, true);

        return $this;
    }

    public function clean()
    {
        $dir = $this->path;

        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);

        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file)
        {
            if ($file->isDir())
            {
                rmdir($file->getRealPath());
            }
            else
            {
                unlink($file->getRealPath());
            }
        }

        $f = new File($this->path . DS . "._DI_");

        $string = "path::$this->path" . NL . "name::$this->name" . NL . "description::$this->description" . NL . "creation_time::$this->creation_time" . NL;

        foreach ($this->attributes as $attribute => $value)
        {
            $string = $string . "$attribute::$value" .NL;
        }

        $f->put($string, true);

        return $this;
    }

    public function delete()
    {
        $dir = $this->path;

        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);

        $files = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach($files as $file)
        {
            if ($file->isDir())
            {
                rmdir($file->getRealPath());
            }
            else
            {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }

    public function host($files, $content = null)
    {
        if (is_array($files))
        {
            foreach ($files as $file)
            {
                if ($file instanceof File)
                {
                    $cnt = $content ? $content : $file->content();

                    $file = $file->basename() ? $file->basename() : uniqid("luna_");
                }

                (new File($this->path . $file))->put($cnt);
            }
        }
        else
        {
            if ($files instanceof File)
            {
                $content = $content ? $content : $files->content();

                $files = $files->basename()? $files->basename() : uniqid("luna_");;
            }

            (new File($this->path . $files))->put($content);
        }


        return $this;
    }
}