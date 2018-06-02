<?php

namespace Luna\ServiceProvider;

use Luna\Core\ServiceProvider;

class Sessions extends ServiceProvider
{
    /**
     * initialize the session
     * @param null $name
     */
    public static function init ( $start = true,  $name = null )
    {
        if ($start)
        {
            if ($name != null)
                session_name ( $name ) ;

            session_save_path(SESSION_PATH );

            if ( session_status() === PHP_SESSION_NONE )
            {
                session_start () ;

                Logger::save('session', 'starting the session ' , 'Success');
            }
            else
                Logger::save('session', 'starting is already on! ' , 'Warning');
        }
    }

    /**
     * insert a variable to the sessions
     * @param $variable
     * @param $value
     */
    public static function add ($variable, $value , $trigger = false)
    {
        if ( ! key_exists($variable, $_SESSION) || $trigger)
        {
            $_SESSION [ $variable ] = $value ;

            Logger::save('session', "adding a new session [ $variable = $value ] "  , 'Success');
        }
        else
        {
            Logger::save('session', "couldn't add to session  [ $variable = $value ] value already exist."  , 'Fail');
        }
    }

    /**
     * @param array $variables has many variables to insert to session
     */
    public static function insert ( array $variables , $trigger = false)
    {
        Logger::save('session', "inserted an array to session", 'Success');

        foreach ($variables as $key => $value){
            self::add ($key, $value, $trigger) ;
        }
    }

    /**
     * session name exist
     * @param $key
     * @return bool
     */
    public static function exist($key)
    {
        return key_exists($key, $_SESSION);
    }

    /**
     * session name exist and not null
     * @param $key
     * @return bool
     */
    public static function exist_nn($key)
    {
        return key_exists($key, $_SESSION) && ($_SESSION[$key] != null);
    }
    /**
     * get the value of a session
     */
    public static function get( $key )
    {
        return self::exist($key) ? $_SESSION[$key] : false;
    }

    /**
     * modify the value of an existing session
     * @param $variable
     * @param $value
     * @return bool
     */
    public static function edit( $variable , $value ,$trigger = false)
    {
        if (self::exist($variable) || $trigger)
            $_SESSION[$variable] = $value;

        else
            return false;

        return true;
    }
    /**
     * Re-initialize session array with original values
     */
    public static function reset ()
    {
        session_reset () ;

        Logger::save('session', "resetting the session values", 'Success');
    }

    /**
     * unset certain session
     * @param null $name
     */
    public static function unset ($name = null)
    {
        if ( $name == null )
        {
            session_unset () ;

            unset($_SESSION);

            Logger::save('session', "unsetting  session", 'Success');
        }
        else
        {
            Logger::save('session', "unsetting session [ $name ]", 'Success');

            unset($_SESSION[$name]);
        }
    }

    /**
     * close the session with saving the data
     */
    public static function close ()
    {
        session_abort () ;

        Logger::save('session', "closing the session", 'Success');

    }

    /**
     * remove all the stored data of a session
     */
    public static function destroy()
    {
        session_destroy();

        Logger::save('session', "destroying the session", 'Success');
    }

    public static function dump()
    {
        echo "<pre>".print_r($_SESSION, true)."</pre>";
    }
}