<?php

namespace Luna\lib\migration\src;


class SQLgenerator
{
    public static function table(array $table)
    {
        if (isset($table['table_name']) && isset($table['attribute']))
        {
            $sql = "CREATE TABLE IF NOT EXISTS `" . $table['db_name'] .  "` . `" . $table['table_name'] .  "` (";

            $i = 0;

            foreach ($table['attribute'] as $attribute => $values)
            {
                $atr = "`$attribute` ";

                //var_dump($atr);

                if ( isset( $values['type'] ) )

                    $atr = $atr . $values['type'] . " ";

                if ( $values['type'] == "enum" || $values['type'] == "set")
                {
                    $atr = $atr ."(";
                    $c = 0;
                    foreach ($values['values'] as $a)
                    {
                        $atr = $atr . '"' . $a . '"';

                        if ($c < count($values['values']) -1)

                            $atr = $atr . ",";
                    $c++;
                    }
                    $atr = $atr . ") ";
                }
                elseif ( isset( $values['length'] ) )

                    $atr = $atr . "(" . $values['length'] . ") ";

                if ( isset( $values['attribute'] ) )

                    $atr = $atr .  $values['attribute'] . " ";

                if ( isset( $values['null'] ) && !$values['null'] )

                    $atr = $atr . " NOT NULL ";

                if ( isset( $values['default'] )  )

                    $atr = $atr . " DEFAULT '" . $values['default'] . "' ";

                if ( isset( $values['auto_increment'] ) && $values['auto_increment'] )

                    $atr = $atr . " AUTO_INCREMENT  ";

                $sql = $sql .$atr ;

                if ($i < count($table['attribute']) - 1)
                {
                    $sql = $sql . ',';
                }
                $i++;

            }
            if (isset($table['primary_key']))

                $sql = $sql . ", PRIMARY KEY (`". $table['primary_key'] ."`)" ;

            if (isset($table['unique']))

                $sql = $sql . ", UNIQUE (`". $table['unique'] ."`)" ;

            if (isset($table['index']))

                $sql = $sql . ", INDEX (`". $table['index'] ."`)" ;

            if (isset($table['other_key']))

                $sql = $sql . ", ". $table['other_key']['key_name'] ." (`". $table['other_key']['column'] ."`)" ;

            $sql = $sql . ")";

            if (isset($table['engine']))

                $sql = $sql . " ENGINE = " . $table['engine'];

            if (isset($table['charset']))

                $sql = $sql . " CHARSET = " . $table['charset'];

            return $sql . " ;";
        }
    }

    public static function drop($table)
    {
        return "DROP TABLE `". $table ."`";
    }

    public static function insert($data)
    {

        if (isset($data['into']) && isset($data['values']))
        {
            $sql = "INSERT INTO `" . $data['into'] . "` (" ;

            $c = 0;

            foreach ($data["values"] as $datum => $values)
            {
                $sql = $sql . "`" . $datum . "`";

                if ($c < count($data['values']) -1)

                    $sql = $sql . ",";

                $c++;
            }

            $sql = $sql . ") VALUES (";

            $c = 0;

            foreach ($data["values"] as $datum )
            {
                $sql = $sql . "'" . $datum . "'";

                if ($c < count($data['values']) -1)

                    $sql = $sql . ",";

                $c++;
            }

            $sql = $sql . ") ;";

        }
        return $sql;
    }

    public static function update(array $row)
    {
        if (isset($row['table']) && isset($row['condition']) && isset($row['values']))
        {
            $sql = "UPDATE `" . $row['table'] . "` SET ";

            $c=0;

            foreach ($row['values'] as $key =>$value )
            {
                $sql = $sql . "`$key` = '$value' ";


                if ($c < count($row['values']) -1)
                    $sql = $sql . ',';

                $c++;
            }
            $sql = $sql . " WHERE `" . $row['table'] . "`." . $row['condition'];

            return $sql . " ; ";
        }
        return FAIL;
    }

    public static function delete(array $row)
    {
        if (isset($row['table']) && isset($row['condition']))

            return  "DELETE FROM `" . $row['table'] . "` WHERE `" . $row['table'] . "`." . $row['condition']  ;

        return FAIL;
    }
}