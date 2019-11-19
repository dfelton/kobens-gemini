<?php

namespace Kobens\Gemini\Api;

use Kobens\Core\Config;

class Host implements HostInterface
{
    public function getHost(): string
    {
        return Config::getInstance()->get('gemini')->api->host;
    }

    public function __toString(): string
    {
        return $this->getHost();
    }
}
