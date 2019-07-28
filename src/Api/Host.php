<?php

namespace Kobens\Gemini\Api;

use Kobens\Core\Config;

/**
 * Class Host
 * @package Kobens\Gemini\Api
 * @deprecated
 */
class Host
{
    /**
     * @return string
     * @deprecated
     */
    public function getHost() : string
    {
        trigger_error(sprintf(
            'The method "%s" has been deprecated, use Config::getApiHost() instead.', __METHOD__
        ), \E_USER_DEPRECATED);
        return Config::getInstance()->get('gemini')->api->host;
    }

    public function __toString() : string
    {
        return $this->getHost();
    }
}