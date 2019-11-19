<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

interface GetNotationalVolumeInterface
{
    public function getVolume(): \stdClass;
}
