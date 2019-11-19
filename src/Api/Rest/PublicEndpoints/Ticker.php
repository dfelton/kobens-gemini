<?php

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

final class Ticker extends AbstractPublicRequest implements TickerInterface
{
    private const URL_PATH = '/v1/pubticker/';

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
