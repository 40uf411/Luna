<?php

namespace Luna\Core;
use Luna\services\Http\{Request, Response};


use Luna\Helpers\Loader;

class Middleware
{
    public static function handle(Request $request, $middlewares)
    {

        if (empty($middlewares))
        {
            return true;
        }
        else
        {
            foreach ($middlewares as $middleware)
            {
                Loader::middleware($middleware);

                $middleware = new $middleware;

                if ( method_exists($middleware,'run') )
                {
                    $middleware->run();
                }
                else
                {
                    $middleware();
                }
            }
        }
        return true;
    }

}