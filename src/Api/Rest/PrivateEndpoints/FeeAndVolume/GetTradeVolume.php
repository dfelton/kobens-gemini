<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;

final class GetTradeVolume implements GetTradeVolumeInterface
{
    private const URL_PATH = '/v1/tradevolume';

    private RequestInterface $request;
    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    /**
     * @return \stdClass
     * @throws \Kobens\Core\Exception\ConnectionException
     * @throws \Kobens\Core\Exception\Http\RequestTimeoutException
     * @throws \Kobens\Gemini\Exception
     * @throws \Kobens\Gemini\Exception\InvalidResponseException
     * @throws \Kobens\Gemini\Exception\ResourceMovedException
     */
    public function getVolume(): array
    {
        $response = $this->request->getResponse(self::URL_PATH, [], [], true);
        return \json_decode($response->getBody());
    }
}
