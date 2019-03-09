<?php

namespace Kobens\Gemini\App;

use Zend\Cache\StorageFactory;

class Cache
{
    public function getCache()
    {
        return StorageFactory::factory((new Config())->cache);
    }
}