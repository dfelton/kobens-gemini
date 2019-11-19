<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume;

interface GetTradeVolumeInterface
{
    public function getVolume(): \stdClass;
}
