<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

interface GetNotationalVolumeInterface
{
    public function getVolume(): \stdClass;
}
