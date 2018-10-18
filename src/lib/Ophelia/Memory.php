<?php
namespace Luna\lib\Ophelia;


use Luna\Andromeda\Andromeda;
use Luna\services\Timer\Time;
use Luna\services\Crypt;

class Memory
{
    private $key;
    private $value;
    private $expire_date;
    private $forget = false;
    private $secure = false;
    private $pass;

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getExpireDate()
    {
        return $this->expire_date;
    }

    /**
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @return mixed
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @return bool
     */
    public function isForget(): bool
    {
        return $this->forget;
    }
    
    

    /**
     * Memory constructor.
     * @param $value
     * @throws \Error
     */
    public function __construct($value)
    {
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
        $this->secure = true;

        $this->pass = Crypt::password($password,PASSWORD_DEFAULT,['cost'=>12]);

        return $this;
    }

    /**
     * @return $this
     */
    public function forget_if_exist()
    {
        $this->forget = true;

        return $this;
    }

    public function save()
    {
        Memorise::save($this);

        return $this;
    }
}