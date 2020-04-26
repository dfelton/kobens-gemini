<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

interface GetNotionalVolumeInterface
{
    public function getVolume(): \stdClass;
}
