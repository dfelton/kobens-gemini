<?php

namespace KobensTest\Gemini\Assets;

use Zend\Cache\Storage\Adapter\Filesystem;

class Cache extends Filesystem
{
    public function __construct()
    {
        parent::__construct(['cache_dir' => '/tmp/kobens-test/kobens-gemini']);
    }
}