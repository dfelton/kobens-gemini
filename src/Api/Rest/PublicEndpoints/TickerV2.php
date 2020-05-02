<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

final class TickerV2 extends AbstractPublicRequest implements TickerV2Interface
{
    private const URL_PATH = '/v2/ticker/';

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
