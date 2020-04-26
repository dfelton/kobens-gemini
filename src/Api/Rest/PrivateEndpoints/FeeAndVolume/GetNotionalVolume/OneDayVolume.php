<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolume;

final class OneDayVolume implements \JsonSerializable
{
    private $date;

    private $notionalVolume;

    public function __construct(string $date, string $notionalVolume)
    {
        $this->date = $date;
        $this->notionalVolume = $notionalVolume;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getNotionalVolume(): string
    {
        return $this->notionalVolume;
    }

    public function jsonSerialize()
    {
        return [
            'date' => $this->date,
            'notional_volume' => $this->notionalVolume,
        ];
    }
}
