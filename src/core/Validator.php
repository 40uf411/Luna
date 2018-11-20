<?php

namespace Luna\Core;


class Validator
{
    //TODO : continue working on the error and arrays
    private $isValid = true;
    private $tmp = null;
    private $data;
    private $errors;
    private $valid = [];
    private $invalid = [];

    public function from($data_source)
    {
        $this->data = $data_source;

        return $this;
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
    public function __invoke()
    {
        return $this->isValid;
    }
    public function __set($name, $value){}
    public function __get($name)
    {
        switch ($name)
        {
            case "ok":
                return $this->isValid;
                break;
            case "failed":
                return  ! $this->isValid;
                break;
            case "valid_keys":
                return $this->valid;
                break;
            case "invalid_keys":
                return $this->invalid;
                break;
            case "errors":
                return $this->errors;
                break;
        }
    }

    public function equals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( ! $this->data[$this->tmp] != $value){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " doesn't equal $value.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function notEquals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            if( $this->data[$this->tmp] == $value ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " doesn't equal $value.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function biggerThan($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            if( $this->data[$this->tmp] <= $value){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is smaller or equals $value.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function biggerOrEquals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            if( $this->data[$this->tmp] < $value){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is smaller than $value.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function smallerThan($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            if( $this->data[$this->tmp] >= $value){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is bigger or equals $value.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;
        return $this;
    }
    public function smallerOrEquals($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))
            if( $this->data[$this->tmp] > $value){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is bigger than $value.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function between(...$values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( ! (count($values) == 2 and ($this->data[$this->tmp] >= $values[0] and $this->data[$this->tmp] <= $values[1])) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is not between " . $values[0] . " and " . $values[1];
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function notBetween(...$values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( (count($values) == 2 and ($this->data[$this->tmp] >= $values[0] and $this->data[$this->tmp] <= $values[1])) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is not between " . $values[0] . " and " . $values[1];
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function in(...$values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( ! in_array($this->data[$this->tmp], $values) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is not in the given values.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function notIn(...$values)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if(  in_array($this->data[$this->tmp], $values) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is in the given values.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function matches($pattern)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( ! preg_match_all($pattern,$this->data[$this->tmp]) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " does not match the given pattern.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function doesNotMatch($pattern)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( preg_match_all($pattern,$this->data[$this->tmp]) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " matches the given pattern.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function is($type)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( ! is($this->data[$this->tmp], $type) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is not a $type or type not defined.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function isNot($type)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( is($this->data[$this->tmp], $type) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " is a $type or type not defined.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function verify(callable $callback)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( ! $callback($this->data[$this->tmp]) ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " does not verify the given function.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }


}