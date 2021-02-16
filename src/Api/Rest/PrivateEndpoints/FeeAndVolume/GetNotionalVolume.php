<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolume\Response;

final class GetNotionalVolume implements GetNotionalVolumeInterface
{
    private const URL_PATH = '/v1/notionalvolume';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function getVolume(): Response
    {
        $response = $this->request->getResponse(self::URL_PATH, [], [], true);
        return new Response($response->getBody());
    }
}
