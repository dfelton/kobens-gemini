<?php

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

final class TickerV2 extends AbstractPublicRequest implements TickerV2Interface
{
    private const URL_PATH = '/v2/ticker/';

    /**
     * @var string
     */
    private $symbol;

    public function getData(string $symbol): \stdClass
    {
        $this->symbol = $symbol;
        return \json_decode($this->getResponse()['body']);
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH.$this->symbol;
    }

}