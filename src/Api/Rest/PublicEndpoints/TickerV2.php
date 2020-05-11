<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

final class TickerV2 implements TickerV2Interface
{
    private const URL_PATH = '/v2/ticker/';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    public function getData(string $symbol): \stdClass
    {
        $response = $this->request->getResponse(self::URL_PATH . $symbol);
        return \json_decode($response->getBody());
    }
}
