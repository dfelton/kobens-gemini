<?php

namespace Kobens\Gemini\App;

use Zend\Config\Config as ZendConfig;

class Config
{
    /**
     * @var ZendConfig
     */
    protected static $config;

    public function __construct(
        ZendConfig $config = null
    )
    {
        if (static::$config === null && $config === null) {
            throw new \Exception(\sprintf(
                'First time instantiation of "%s" requires an object of "%s"',
                __CLASS__,
                ZendConfig::class
            ));
        } elseif (static::$config !== null && $config !== null) {
            throw new \Exception(\sprintf(
                '"%s" cannot be re-instantiated with new config.',
                __CLASS__
            ));
        } elseif (static::$config === null && $config !== null) {
            static::$config = $config;
        }
    }

    public function get(string $name) : ZendConfig
    {
        return static::$config->get($name);
    }

    public function __get(string $name)
    {
        return $this->get($name);
    }
}