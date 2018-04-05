<?php

namespace Luna\Providers;

use PDO;

class DatabaseProvider
{
    protected $db;          //the db connection

    protected $sql;         //sql statement

    /**
     * Constructor, to connect to database, select database and set charset
     */
    public function __construct(){

        $config = db_config;

        $db_host = isset($config['host'])? $config['host'] : 'localhost';

        $db_user = isset($config['user'])? $config['user'] : 'root';

        $db_pass = isset($config['password'])? $config['password'] : '';

        $db_name = isset($config['name'])? $config['name'] : 'tye';

        $db_charset = isset($config['charset'])? $config['charset'] : 'utf8';

        try {

            $this->db = new PDO("mysql:host=$db_host; dbname=$db_name", $db_user , $db_pass,[
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC
            ]);

            Log::save('database', 'connecting to database : ' . $db_name , 'Success');

            $this->setChar($db_charset);

        } catch(PDOException $e)
        {
            ?>
            <div style="font-size: 3em; position: absolute; left: 50%; transform: translate(-50%,0); top: 5%; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,Arial,sans-serif; ">
                <b>Oops! </b> Failed to connect to databese </br>
                <div style="font-size:.5em;margin-top:30px">'
                    <?php echo $e->getMessage() ?>
                </div>
            </div>';
            <?php

            Log::save('database', 'connecting to database ' , 'Fail');

            die();
        }
    }

    /**
     * close the connection with thr database
     */
    public function __destruct()
    {
        $this->db = null;

        unset( $this->db );

        Log::save('database', 'closing to database ' , 'Success');
    }

    /**
     * set the charset
     * @param string $charest
     */
    private function setChar($charest){

        $sql = 'set names '.$charest;

        $this->query($sql);

    }

    /**
     * Execute SQL statement
     * @access public
     * @param $sql string SQL query statement
     * @return $result，if succeed, return resrouces; if fail return error message and exit
     */
    public function query($sql){

        $this->sql = $sql;
        try
        {
            $result = $this->db->query($this->sql);

        }
        catch( PDOException $e )
        {

            Log::save('database', $sql , 'Fail');

            $stmt = $this->error()[0] .' : ' . $this->error()[1] . ' | Error SQL statement | ' . $this->sql . ' | ' . $this->error()[2] ;

            Log::save('database', $stmt , 'ERROR' );

            die($stmt);

        }

        Log::save('database', $sql , 'Success');

        return $result;
    }


    /**
     * @param bool $type => true to return an array_Of_Arrays false to return an array (1 row)
     * @param string $what => columns to be fetched from the databaseProvider.class
     * @param string $where => the table where you want to fetch data from
     * @param string $details => any other details such as "WHERE"...
     * @param string $limit => set the limit, default none
     * @return mixed
     */

    public function getData($type=false,$what="",$where="",$details="",$limit=null)
    {
        $llimit = ($limit == null || !is_integer($limit))? null : 'limit = ' . $limit;

        $query = "SELECT $what FROM $where $details  $llimit";

        $stmt = $this->query($query);

        if ($type)

            return $stmt->fetchall();

        else

            return $stmt->fetch();

    }

    /**
     * @param $string object you seek
     * @param string $were the column to check in
     * @param string $table the table to fetch data from
     * @return int the row count
     */
    function exist($string , $were="",$table=""){

        if ($were == "id"){

            $query = "SELECT $were  FROM  $table WHERE $were = '".$string."'";

        } else {

            $query = "SELECT id, $were  FROM  $table WHERE $were = '".$string."'";

        }

        $stmt = $this->query($query);

        if($stmt->rowCount()!=0 )

            return $stmt->fetch()['id'];

        else

            return false;

    }

    public function errno(){

        return $this->db->errorCode();

    }

    /**
     * Get error message
     * @access private
     * @return error message
     */

    public function error(){

        return $this->db->errorInfo();

    }

}