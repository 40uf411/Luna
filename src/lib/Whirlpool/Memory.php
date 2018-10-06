<?php
namespace Luna\lib\Whirlpool;


use Luna\Andromeda\Andromeda;
use Luna\services\Timer\Time;

class Memory
{

    private static $connect = false;

    private $key;
    private $value;
    private $expire_date;
    private $forget = false;
    private $secure = false;
    private $pass;

    public static function config($config)
    {
        self::$connect = Andromeda::connect([
            "name"   => $config['db_name'],
            "user"   => $config['db_user'],
            "pass"   => $config['db_pass']
        ]);
    }

    /**
     * Memory constructor.
     * @param $value
     * @throws \Error
     */
    public function __construct($value)
    {
        if (!self::$connect)
            error("Error! you can't use the memory without connect to databases");

        $this->value = $value;
        $t = (new Time())->now()->addDays(15);

        $this->expire_date['year'] = $t->year();
        $this->expire_date['month'] = $t->month();
        $this->expire_date['day'] = $t->day();
        $this->expire_date['hour'] = $t->hour();
        $this->expire_date['minute'] = $t->minute();
    }

    /**
     * @param $key
     * @return $this
     * @throws \Error
     */
    public function as($key)
    {
        if (!self::$connect)
            error("Error! you can't use the memory without connect to databases");

        $this->key = $key;

        return $this;
    }

    /**
     * @param Time $time
     * @return $this
     * @throws \Error
     */
    public function until(Time $time)
    {
        if (!self::$connect)
            error("Error! you can't use the memory without connect to databases");


        $this->expire_date['year'] = $time->year();
        $this->expire_date['month'] = $time->month();
        $this->expire_date['day'] = $time->day();
        $this->expire_date['hour'] = $time->hour();
        $this->expire_date['minute'] = $time->minute();

        return $this;
    }

    /**
     * @param $password
     * @return $this
     * @throws \Error
     */
    public function with_password($password)
    {
        if (!self::$connect)
            error("Error! you can't use the memory without connect to databases");

        $this->secure = true;

        $this->pass = md5($password);

        return $this;
    }

    public function forget_if_exist()
    {
        $this->forget = true;
    }
}