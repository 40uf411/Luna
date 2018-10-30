<?php

require_once "config" . DS . "symfony_dumper.php";

# shortcuts

function view($name, $data= null, $driver = null, $package = null)
{
    $view = new  \Luna\services\View($driver, $package);

    return $view->render($name, $data);
}

function cookie($name, $value , $expire_time = null, $path = null, $domain = null, $secure = null, $http_only = null )
{
    $c = new \Luna\services\Cookie($name, $value , $expire_time , $path , $domain , $secure , $http_only );

    $c->flush();

    return $c;
}

function session($name, $value)
{
    return new \Luna\services\Session($name, $value);
}

/**
 * @return \Luna\services\Timer\Time
 */
function now()
{
    return (new \Luna\services\Timer\Time())->now();
}

function _file($path)
{
    return new Luna\services\Storage\File($path);
}

function _dir($path, $dirname, $erase)
{
    return new Luna\services\Storage\Dir($path, $dirname, $erase);
}

/**
 * @param string $string
 * @param array $theme
 * @param bool $center
 * @param int $width
 * @throws Error
 */
function printCli($string = "", $theme = [], bool $center  = false, int $width = 80)
{
    (new \Luna\services\Cli\Printer())->print($string,$theme,$center,$width);
}

# casting

function arrayToString(array $array, $form = ' $k = $v ')
{
    $str = null;

    foreach ($array as $key => $item)
    {
        $f = $form;
        $f = str_replace('$k',$key,$f);
        $f = str_replace('$v',$item,$f);

        $str = $str . $f;
    }

    return $str;
}

function arrayToObject(array $array, $className)
{
    return unserialize(sprintf(
        'O:%d:"%s"%s',
        strlen($className),
        $className,
        strstr(serialize($array), ':')
    ));
}

function objectToObject($instance, $className)
{
    return unserialize(sprintf(
        'O:%d:"%s"%s',
        strlen($className),
        $className,
        strstr(strstr(serialize($instance), '"'), ':')
    ));
}

function objectToArray($object)
{
    $reflectionClass = new ReflectionClass(get_class($object));

    $array = array();

    foreach ($reflectionClass->getProperties() as $property) {
        $property->setAccessible(true);
        $array[$property->getName()] = $property->getValue($object);
        $property->setAccessible(false);
    }

    return $array;
}

function hasKeys(array $array,array $keys)
{
    foreach ($keys as $key)
    {
        if (! array_key_exists($key,$array))
            return false;
    }
    return true;
}

function cast($destination, $sourceObject)
{
    if (is_string($destination))
    {
        $destination = new $destination();
    }
    $sourceReflection = new ReflectionObject($sourceObject);

    $destinationReflection = new ReflectionObject($destination);

    $sourceProperties = $sourceReflection->getProperties();

    foreach ($sourceProperties as $sourceProperty)
    {
        $sourceProperty->setAccessible(true);

        $name = $sourceProperty->getName();

        $value = $sourceProperty->getValue($sourceObject);

        if ($destinationReflection->hasProperty($name))
        {
            $propDest = $destinationReflection->getProperty($name);

            $propDest->setAccessible(true);

            $propDest->setValue($destination,$value);
        }
        else
        {
            $destination->$name = $value;
        }
    }
    return $destination;
}

# array

function array_upper_key(array $array)
{
    return array_change_key_case($array,CASE_UPPER);
}

function array_lower_key(array $array)
{
    return array_change_key_case($array,CASE_LOWER);
}

function sort_array(array $array, $mode = 0, ...$flag)
{
    switch ($mode)
    {
        case (L_SORT_ASC):
            return sort($array,$flag);
            break;

        case (L_SORT_DESC):
            return rsort($array,$flag);
            break;

        case (L_SORT_ASC_KEY):
            return asort($array,$flag);
            break;

        case (L_SORT_ASC_VALUE):
            return ksort($array,$flag);
            break;

        case (L_SORT_DESC_KEY):
            return arsort($array,$flag);
            break;

        case (L_SORT_DESC_VALUE):
            return krsort($array);
            break;
    }
}

function keys(array $keys, array $array)
{
    $r = [];

    foreach ($keys as $key)
    {
        $r[$key] = isset($array[$key]) ? $array[$key] : null;
    }

    return $r;
}

# throwing

/**
 * @param null $msg
 * @throws Exception
 */
function exception($msg = null)
{
    throw new Exception($msg);
}

/**
 * @param null $msg
 * @throws Error
 */
function error($msg = null)
{
    throw new Error($msg);
}

# helpers

function is_cli()
{
    return (php_sapi_name() === 'cli');
}

function is($variable, $type, $classname = "stdClass")
{
    $type = strtolower($type);

    switch ($type)
    {
        case "a":
        case "instance of":
            return is_a($variable,$classname);
            break;

        case "ary":
        case "array":
            return is_array($variable);
            break;

        case "b":
        case "bool":
        case "boolean":
            return is_bool($variable);
            break;

        case "func":
        case "function":
        case "callable":
            return is_callable($variable);
            break;

        case "dir":
            return is_dir($variable);
            break;

        case "dbl":
        case "double":
            return is_double($variable);
            break;

        case "exec":
        case "executable":
            return is_executable($variable);
            break;

        case "file":
            return is_file($variable);
            break;

        case "flt":
        case "float":
            return is_float($variable);
            break;

        case "int":
        case "integer":
            return is_integer($variable);
            break;

        case "link":
            return is_link($variable);

        case "long":
            return is_long($variable);
            break;

        case "null":
            return is_null($variable);
            break;

        case "num":
        case "numeric":
            return is_numeric($variable);
            break;

        case "obj":
        case "object":
            is_object($variable);
            break;

        case "real":
            return is_real($variable);
            break;

        case "subclass":
            return is_subclass_of($variable,$classname);
            break;

        case "uploaded":
            is_uploaded_file($variable);
            break;

        default:
            return null;
            break;
    }
}