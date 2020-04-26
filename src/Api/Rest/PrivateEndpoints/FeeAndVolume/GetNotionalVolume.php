<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

final class GetNotionalVolume extends AbstractPrivateRequest implements GetNotionalVolumeInterface
{
    private const URL_PATH = '/v1/notionalvolume';

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
