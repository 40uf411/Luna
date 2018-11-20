<?php

namespace Luna\Core;


class Validator
{
    //TODO : continue working on the error and arrays
    private $isValid = true;
    private $tmp = null;
    private $data;
    private $errors;

    public function from($data_source)
    {
        $this->data = $data_source;
    }
    
    public function check($key)
    {
        $this->tmp = $key;

        return $this;

    }

    public function and($key)
    {
        $this->tmp = $key;

        return $this;
    }

    public function go()
    {
        return $this->isValid;
    }
    public function __call()
    {
        return $this->isValid;
    }

    public function equals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = $this->data[$this->tmp] == $value;

        return $this;
    }
    public function notEquals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = $this->data[$this->tmp] != $value;

        return $this;
    }
    public function biggerThan($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = $this->data[$this->tmp] > $value;

        return $this;
    }
    public function biggerOrEquals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = $this->data[$this->tmp] >= $value;

        return $this;
    }
    public function smallerThan($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = $this->data[$this->tmp] < $value;

        return $this;
    }
    public function smallerOrEquals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = $this->data[$this->tmp] <= $value;

        return $this;
    }
    public function between(array $values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = count($values) == 2 and ($this->data[$this->tmp] >= $values[0] and $this->data[$this->tmp] <= $values[1]);

        return $this;
    }
    public function notBetween(array $values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = count($values) == 2 and ($this->data[$this->tmp] < $values[0] and $this->data[$this->tmp] > $values[1]);

        return $this;
    }
    public function in(array $values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = in_array($this->data[$this->tmp], $values);

        return $this;
    }
    public function notIn(array $values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = !in_array($this->data[$this->tmp], $values);

        return $this;
    }
    public function matches($pattern)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = preg_match_all($pattern,$this->data[$this->tmp]);

        return $this;
    }
    public function is($type)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = is($this->data[$this->tmp], $type);

        return $this;
    }
    public function verify(callable $callback)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            $this->isValid = $callback($this->data[$this->tmp]);

        return $this;
    }


}