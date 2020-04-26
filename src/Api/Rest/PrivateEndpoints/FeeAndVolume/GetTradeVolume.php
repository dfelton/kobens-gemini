<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

final class GetTradeVolume extends AbstractPrivateRequest implements GetTradeVolumeInterface
{
    private const URL_PATH = '/v1/tradevolume';

    public function getVolume(): \stdClass
    {
        return \json_decode($this->getResponse()->getBody());
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH;
    }

    protected function getPayload(): array
    {
        return [];
    }
}
