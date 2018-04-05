<?php

namespace Luna\Providers;

class SessionProvider
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

                Log::save('session', 'starting the session ' , 'Success');
            }
            else
                Log::save('session', 'starting is already on! ' , 'Warning');
        }
    }

    /**
     * insert a variable to the sessions
     * @param $variable
     * @param $value
     */
    public static function add ( $variable, $value )
    {
        if ( ! key_exists($variable, $_SESSION))
        {
            $_SESSION [ $variable ] = $value ;

            Log::save('session', "adding a new session [ $variable = $value ] "  , 'Success');
        }
        else
        {
            Log::save('session', "couldn't add to session  [ $variable = $value ] value already exist."  , 'Fail');
        }
    }

    /**
     * @param array $variables has many variables to insert to session
     */
    public static function insert ( array $variables )
    {
        Log::save('session', "inserted an array to session", 'Success');

        foreach ($variables as $key => $value){
            self::add ($key, $value) ;
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
    public static function edit( $variable , $value )
    {
        if (self::exist($variable))
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

        Log::save('session', "resetting the session values", 'Success');
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

            Log::save('session', "unsetting  session", 'Success');
        }
        else
        {
            Log::save('session', "unsetting session [ $name ]", 'Success');

            unset($_SESSION[$name]);
        }
    }

    /**
     * close the session with saving the data
     */
    public static function close ()
    {
        session_abort () ;

        Log::save('session', "closing the session", 'Success');

    }

    /**
     * remove all the stored data of a session
     */
    public static function destroy()
    {
        session_destroy();

        Log::save('session', "destroying the session", 'Success');
    }

    public static function dump()
    {
        echo "<pre>".print_r($_SESSION, true)."</pre>";
    }
}