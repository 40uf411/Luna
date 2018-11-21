<?php

/**************************************************************
 *
 *                      Luna validator v1
 *
 * ************************************************************
 *
 *   # How to use:
 *
 *       simply call the facade function "make" example:
 *
 *          $v = validator::make();
 *
 *   # Initialising the validator:
 *
 *      to do so you only have to call the function 'init' example:
 *
 *          $v->init();
 *
 *      notice: this is optional in the first use since it has already been initialised.
 *
 *   # select validation subject:
 *
 *      we first start by selecting the data:
 *
 *      if the data is an array you use the function 'from' example:
 *
 *          $v->from($_POST)->.....
 *
 *      and to select which key to validate you use the functions 'check' and 'and' example:
 *
 * *        $v->from($_POST)->check('id')...->and('email')...->and('password')->.....

 *      otherwise if you are not validating an array you call the function 'validate' example:
 *
 *          $this->validate($user)->....
 *
 *      notice: when validating a single value and not an array using the 'check' and 'and' functions will throw an error.
 *
 *
 *   # validation functions:
 *
 *      minLength (int value)
 *      maxLength (int value)
 *      equals (mixed value)
 *      notEquals (mixed value)
 *      biggerThan  (int value)
 *      biggerOrEquals (int value)
 *      smallerThan (int value)
 *      smallerOrEquals (int value)
 *      between ( values... )
 *      notBetween ( values... )
 *      in ( values... )
 *      notIn ( values... )
 *      matches (regular_expression)
 *      doesNotMatch (regular_expression)
 *      is ( type_name ) // uses the function 'is()' written in file /src/helpers/functions.php
 *      isNot ( type_name ) // uses the function 'is()' written in file /src/helpers/functions.php
 *      verify ( callable function )
 *
 *
 *   # validation result:
 *
 *      to get the result you use
 *
 *          # $v() or $v->ok or $v->ok() return true if the validation is done successfully.
 *          # $v->failed or $v->failed() return true if the validation has failed.
 *          # $v->errors or $v->errors() return an array of the errors.
 *          # $v->valid_keys or $v->valid_keys() return an array of the valid keys.
 *          # $v->invalid_keys or $v->invalid_keys() return an array of the invalid keys.
 *
 *   # usage example:
 *
 *          $v = Validator::make();
 *          $v ->from(["username" => "Ali", "password" => "hello123", "age" => 18, "email" => "ali@email.com", "gender" => "male"]) // or in case of forms $v ->from($_POST)
 *             ->check("username")->minLength(8)
 *             ->and("password")->minLength(8)->notIn("pass", "password", "admin", "admin123")->matches("[a-zA-Z0-9]*")
 *             ->and("age")->between(18,70)
 *             ->and("email")->is("email")
 *             ->and("gender")->in("male", "female", "other")
 *             ->and("password")->verify(function($pass, $data){ // testing if a certain user exists in the database, note that you can do the same thing by checking on the username...
 *                      $username = $data['username']; // the data variable is the array passed in the function 'from'
 *
 *                      ...
 *
 *                      $results = $db->query("select * from users where username = $username and password = $pass")->fetchAll();
 *
 *                      ...
 *
 *                      return count($results) > 0;
 *
 *                      or
 *
 *                      return [
 *
 *                              "status" => count($results) > 0,
 *                              "error_message" => "Sorry! username or password is wrong!" // the error message will only be added if the status equals false
 *                      ];
 *              });
 *
 *          if( $v->ok )
 *
 *              echo "hello $username! i hope you are having a great day!".
 *
 *          else
 *
 *              echo "Opps! there something went wrong!";
 *
 *              foreach( error in $v->errors )
 *
 *                  echo $error;
 *
 */

namespace Luna\Core;


class Validator
{

    private static $built = false;
    private static $elem;

    public static function make()
    {
        if (self::$built)
            return self::$elem;
        else{
            self::$built = true;
            self::$elem = new self;
            return self::$elem;
        }
    }

    private $isValid = true;
    private $tmp = null;
    private $data;
    private $errors;
    private $valid = [];
    private $invalid = [];
    private $elem_mode = false;


    public function init()
    {
        $this->isValid = true;
        $this->elem_mode = true;
        $this->tmp = null;
        $this->data;
        $this->errors;
        $this->valid = [];
        $this->invalid = [];

        return $this;
    }

    public function from($data_source)
    {
        $this->elem_mode = true;

        $this->data = $data_source;

        return $this;
    }

    public function validate($value)
    {
        $this->elem_mode = true;

        $this->tmp = "tmp";

        $this->data["tmp"] = $value;

        return $this;
    }

    /**
     * @param $key
     * @return $this
     * @throws \Error
     */
    public function check($key)
    {
        if ($this->elem_mode)
            throw new \Error("you can't use check function when validating is not on array");
        $this->tmp = $key;

        return $this;

    }

    /**
     * @param $key
     * @return $this
     * @throws \Error
     */
    public function and($key)
    {
        if ($this->elem_mode)
            throw new \Error("you can't use check function when validating is not on array");

        $this->tmp = $key;

        return $this;
    }

    private function __construct(){}
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
    public function __call($name)
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

    public function minLength($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( strlen($this->data[$this->tmp]) < $value){
                $this->isValid = false;
                $this->errors[] = "the string " . $this->data[$this->tmp] . " is under min length '$value'.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }
    public function maxLength($value)
    {
        if ($this->isValid and array_key_exists($this->tmp,$this->data))

            if( strlen($this->data[$this->tmp]) < $value){
                $this->isValid = false;
                $this->errors[] = "the string " . $this->data[$this->tmp] . " has passed the max length allowed '$value'.";
                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
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

            $result = $callback($this->data[$this->tmp], $this->data);
            $err = null;
            $check = $result;

            if (is($result,"array"))
            {
                $err =  array_key_exists("error_message", $result)? $result["error_message"] : null;
                $check =  array_key_exists("status", $result)? $result["status"] : $result;
            }

            if( ! $check ){
                $this->isValid = false;
                $this->errors[] = "the value " . $this->data[$this->tmp] . " does not verify the given function.";
                if (! empty($err))
                    $this->errors[] = $err;

                $this->invalid[] = $this->tmp;
            }
            else
                $this->valid[] = $this->tmp;

        return $this;
    }


}