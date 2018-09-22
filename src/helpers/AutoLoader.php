<?php

namespace Luna\Helpers;

class Autoloader
{
    public static function init()
    {
        spl_autoload_register( 'autoload' );
    }
    public static function autoload( $class, $dir = null )
    {
        if ( is_null( $dir ) )
            $dir = __DIR__;

        foreach ( scandir( $dir ) as $file ) {

            // directory?
            if ( is_dir( $dir.$file ) && substr( $file, 0, 1 ) !== '.' )
                autoload( $class, $dir.$file.'/' );

            // php file?
            if ( substr( $file, 0, 2 ) !== '._' && preg_match( "/.php$/i" , $file ) ) {

                // filename matches class?
                if ( str_replace( '.php', '', $file ) == $class || str_replace( '.class.php', '', $file ) == $class ) {

                    include $dir . $file;
                }
            }
        }
    }
}