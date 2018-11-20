<?php
namespace Luna\services\Stream;


class FileStream
{
    private $path;
    private $type;
    private $chunk_size;
    
    public function __construct($filePath, $type, $chunk_size = 1024*1024)
    {
        $this->path = $filePath;
        $this->type = $type;
        $this->chunk_size = $chunk_size;
    }

    public function readFile_chunk($retbytes = TRUE)
    {
        $buffer = '';
        $cnt    = 0;
        $handle = fopen($this->path, 'rb');

        if ($handle === false) {
            return false;
        }

        while (!feof($handle)) {
            $buffer = fread($handle, $this->chunk_size);
            echo $buffer;
            ob_flush();
            flush();

            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }

        $status = fclose($handle);

        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }

        return $status;
    }

    public function start()
    {
        header('Content-Type: '.$this->type );
        $this->readFile_chunk();
    }
}