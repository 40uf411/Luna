<?php

namespace Luna\services\Cli;


class Scanner
{
    /**
     * @param $prompt
     * @return string
     */
    public function nextLine($prompt = null)
    {
        $line = readline((string) $prompt);

        if (!empty($line)) {
            readline_add_history($line);
        }

        return $line;
    }

    /**
     * @param string|null $prompt
     * @return string
     * @throws \Exception
     */
    public function nextInt( $prompt = null)
    {
        $line = $this->nextLine($prompt);

        if ($line != "0" and intval($line) == 0)
        {
            exception("Exception: miss match type, expected integer, received string");
        }

        return intval($line);
    }

    /**
     * @param string|null $prompt
     * @return string
     * @throws \Exception
     */
    public function nextFloat( $prompt = null)
    {
        $line = $this->nextLine($prompt);

        if ($line != "0" and floatval($line) == 0)
        {
            exception("Exception: miss match type, expected float, received string");
        }

        return floatval($line);
    }


    /**
     * @param string|null $prompt
     * @return string
     * @throws \Exception
     */
    public function nextDouble( $prompt = null)
    {
        $line = $this->nextLine($prompt);

        if ($line != "0" and doubleval($line) == 0)
        {
            exception("Exception: miss match type, expected double, received string");
        }

        return doubleval($line);
    }


    /**
     * @param string|null $prompt
     * @return string
     * @throws \Exception
     */
    public function nextBool( $prompt = null)
    {
        return boolval($this->nextLine($prompt));
    }

    /**
     * @param $confirm
     * @param null $cancel
     * @param null $prompt
     * @return int
     */
    public function prompt($confirm, $cancel = null, $prompt = null)
    {
        $s = strtolower( $this->nextLine($prompt) );

        if (is($confirm,'ary'))
            array_walk($confirm,function (&$item, $key){ $item = strtolower($item); });
        else
            $confirm = strtolower($confirm);

        if (is($cancel,'ary'))
            array_walk($cancel,function (&$item, $key){ $item = strtolower($item); });
        else
            $cancel = strtolower($cancel);

        if (  (is($confirm,'ary') and in_array($s,$confirm) ) or ( $s === $confirm ) )
        {
            return 1 ;
        }

        if ($cancel == null)
        {
            return -1;
        }
        else
        {
            if ( (is($cancel,'ary') and in_array($s,$cancel) ) or ( $s === $cancel ) )
                return -1;

            else
                return 0;
        }
    }

    /**
     * @param array $expectations
     * @param null $prompt
     * @return string
     * @throws \Error
     */
    public function expect(array $expectations, $prompt = null)
    {
        $s = $this->nextLine($prompt);

        if (in_array($s, $expectations))
            return $s;

        else
            error("Error! unexpected input.");

    }
}