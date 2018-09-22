<?php
use src\services\Router;

class routesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @tests
     */
    public function right_root()
    {
        var_dump(
            src\services\Router::matchUrl('')
        );
        $this->assertSame(
            src\services\Router::matchUrl('')->callback
            , ""
        );
    }
}