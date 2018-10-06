<?php

namespace Luna\services\Cli;


class Table
{
    private $cols = [];
    private $max_col_length = [];
    public $rows;
    private $configs = [
        "line" => "-",
        "col"  => "|"
    ];

    public function __construct(array $cols = [])
    {
        $this->cols = $cols;

        foreach ($cols as $col)
        {
            $this->max_col_length[$col] = strlen($col);
        }
    }

    /**
     * @param string $colname
     * @return $this
     * @throws \Error
     */
    public function addCol(string $colname)
    {
        if (in_array($colname,$this->cols))
            error("column with same name '$colname' exists in the table");

        $this->cols = array_merge($this->cols,[$colname]);

        $this->max_col_length[$colname] = strlen($colname);

        return $this;
    }

    /**
     * @param array $cols
     * @return $this
     * @throws \Error
     */
    public function addCols(array $cols)
    {
        foreach ($cols as $col)
        {
            $this->addCol($col);
        }

        return $this;
    }

    public function insert(array $row)
    {
        if (empty($row))
            return null;

        $id = uniqid();

        foreach ($this->cols as $col)
        {
            if (array_key_exists($col,$row))
            {
                if (strlen((string) $row[$col]) > $this->max_col_length[$col])

                    $this->max_col_length[$col] = strlen((string) $row[$col]);

                $this->rows[$id][$col] = $row[$col];

            }
            else
                $this->rows[$id][$col] = " ";
        }

        return $this;

    }

    private function separator()
    {
        $s = "  ";
        $l = count($this->cols) + 1;
        foreach ($this->max_col_length as $item)
        {
            $l += $item + 3;
        }

        for ($i = 0;$i < $l;$i++)
            $s .= $this->configs['line'];

        return $s;
    }

    public function render()
    {
        $s = $this->separator() . NL;

        $s  .= "  " . $this->configs['col'];

        foreach ($this->cols as $key => $col)
        {
            $c = $col;

            $s .= " ";

            if (strlen($col) < $this->max_col_length[$col])
            {
                do
                {
                    $col .= " ";
                }while(strlen($col) <  $this->max_col_length[$c]);

            }

            $s .= $col . "  " . $this->configs['col'];
        }
        $s .= NL .$this->separator() . NL;

        foreach ($this->rows as $row)
        {
            $s  .= "  " . $this->configs['col'];

            foreach ($row as $key => $col)
            {
                $s .= " ";
                if (strlen($col) < $this->max_col_length[$key])
                    do
                    {
                        $col .= " ";
                    }while(strlen($col) <  $this->max_col_length[$key]);

                $s .= $col . "  " . $this->configs['col'];

            }
            $s .= NL;
        }
        $s .= $this->separator() . NL;
        return $s;
    }

    public function setChar(string $char, string $set)
    {
        switch ($char)
        {
            case "line":
                $this->configs[$char] = $set;
                break;
            case "col":
                $this->configs[$char] = $set;
                break;
        }

        return $this;
    }

    public function clean()
    {
        $this->rows = [];

        return $this;
    }

    public function drop()
    {
        $this->cols = [];
        $this->max_col_length = [];
        $this->rows = [];
        $this->configs = [
            "line" => "-",
            "col"  => "|"
        ];

        return $this;
    }
}