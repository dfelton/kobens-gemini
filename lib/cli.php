<?php

class Cli
{
    protected static $_args = [];

    protected static $argsParsed = false;

    /**
     * @param string $name
     * @return boolean|mixed
     */
    public static function getArg($name)
    {
        if (!static::$argsParsed) {
            static::_parseArgs();
        }
        return array_key_exists($name, static::$_args) ? static::$_args[$name] : false;
    }

    /**
     * Parse input arguments
     */
    protected static function _parseArgs()
    {
        $current = null;
        foreach ($_SERVER['argv'] as $arg) {
            $match = [];
            if (preg_match('#^--([\w\d_-]{1,})$#', $arg, $match) || preg_match('#^-([\w\d_]{1,})$#', $arg, $match)) {
                $current = $match[1];
                static::$_args[$current] = true;
            } else {
                if ($current) {
                    static::$_args[$current] = $arg;
                } else if (preg_match('#^([\w\d_]{1,})$#', $arg, $match)) {
                    static::$_args[$match[1]] = true;
                }
            }
        }
    }

}