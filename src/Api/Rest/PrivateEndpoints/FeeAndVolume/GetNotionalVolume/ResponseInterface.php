<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolume;

interface ResponseInterface extends \JsonSerializable
{
    public function getDate(): string;

    public function getLastUpdatedMs(): int;

    /**
     * @return OneDayVolume[]
     */
    public function getNotional1DayVolume(): array;

    public function getNotional30DayVolume(): string;

    public function getApiMakerFeeBPS(): int;

    public function getApiTakerFeeBPS(): int;

    public function getBlockMakerFeeBPS(): int;

    public function getBlockTakerFeeBPS(): int;

    public function getFixMakerFeeBPS(): int;

    public function getFixTakerFeeBPS(): int;

    public function getWebMakerFeeBPS(): int;

    public function getWebTakerFeeBPS(): int;
}
