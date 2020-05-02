<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

final class Ticker extends AbstractPublicRequest implements TickerInterface
{
    private const URL_PATH = '/v1/pubticker/';

    private string $symbol;

    public function getData(string $symbol): \stdClass
    {
        $this->symbol = $symbol;
        return \json_decode($this->getResponse()->getBody());
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH . $this->symbol;
    }

}
