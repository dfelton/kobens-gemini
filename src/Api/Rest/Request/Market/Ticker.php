<?php

namespace Kobens\Gemini\Api\Rest\Request\Market;

use Kobens\Gemini\Api\Rest\Request;

class Ticker extends Request
{
    const CURLOPT_POST = false;

    private $symbol;

    public function __construct(string $symbol)
    {
        $this->symbol = $symbol;
        parent::__construct();
    }

    protected function getUrlPath(): string
    {
        return '/v1/pubticker/'.$this->symbol;
    }

}
