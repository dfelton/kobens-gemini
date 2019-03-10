<?php

namespace Kobens\Gemini\Api;

use Kobens\Core\Config;

class Host
{
    public function getHost() : string
    {
        return (new Config())->gemini->api->host;
    }

    public function __toString() : string
    {
        return $this->getHost();
    }
}