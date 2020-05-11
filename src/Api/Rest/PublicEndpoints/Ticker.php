<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PublicEndpoints;

final class Ticker implements TickerInterface
{
    private const URL_PATH = '/v1/pubticker/';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function getData(string $symbol): \stdClass
    {
        $response = $this->request->makeRequest(self::URL_PATH . $symbol);
        return \json_decode($response->getBody());
    }

}
