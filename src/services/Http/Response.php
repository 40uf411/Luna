<?php
/**
 * Credit : (c) Fabien Potencier <fabien@symfony.com>
 */

namespace Luna\services\Http;


class Response
{

    protected $headers = [];

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var int
     */
    protected $statusCode;

    /**
     * @var string
     */
    protected $statusText;

    /**
     * @var string
     */
    protected $charset;

    private static $instance;
    private static $init = false;

    /**
     * @return Response
     * @throws \Error
     */
    public static function instance()
    {
        if (! self::$init)
        {
            self::$instance = new self();

            self::$init = true;

            return self::$instance;
        }
        else
            return self::$instance;
    }

    public function __get($var)
    {
        return null;
    }

    public function __set($var, $val)
    {

    }
    
    private function __construct()
    {

    }

    public function header($header, $value = null)
    {
        $this->headers[] = new Header($header, $value);
    }

    public function headers(array $headers)
    {
        foreach ($headers as $header => $value)
        {
            $this->headers[] = new Header($header, $value);
        }
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     *
     */
    public function flush()
    {
        foreach ($this->headers as $header )
        {
            header("$header");
        }
    }

    public function redirect(string $link)
    {
        if ( !headers_sent())
        {
            header("location:" . $link);
        }
        else
        {
            echo "<script type='text/javascript'>window.location.href=$link</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url=$link'> </noscript>";
        }
    }

    public function status($code)
    {
        if (StatusCodes::exist($code))

            header("HTTP/1.0 $code " . StatusCodes::code($code));
    }

}