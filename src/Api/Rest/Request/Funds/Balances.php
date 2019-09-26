<?php

namespace Kobens\Gemini\Api\Rest\Request\Funds;

use Kobens\Gemini\Api\Rest\Request;

class Balances extends Request
{
    const REQUEST_URI = '/v1/balances';

    protected function getUrlPath()
    {
        return self::REQUEST_URI;
    }

}
