<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolume;

final class Response implements ResponseInterface
{
    private string $date;

    private int $lastUpdatedMs;

    /**
     * @var OneDayVolume[]
     */
    private array $notional1DayVolume = [];

    private string $notional30DayVolume;

    /**
     * @var int[]
     */
    private $bps = [
        'api_auction_fee_bps' => null,
        'api_maker_fee_bps' => null,
        'api_taker_fee_bps' => null,
        'block_maker_fee_bps' => null,
        'block_taker_fee_bps' => null,
        'fix_auction_fee_bps' => null,
        'fix_maker_fee_bps' => null,
        'fix_taker_fee_bps' => null,
        'web_auction_fee_bps' => null,
        'web_maker_fee_bps' => null,
        'web_taker_fee_bps' => null,
    ];

    public function __construct(string $responseBody)
    {
        $this->init($responseBody);
    }

    private function init(string $responseBody)
    {
        /** @var \stdClass $obj */
        $obj = @\json_decode($responseBody);
        if ($obj === null) {
            throw new \Exception("Unable to decode response string.");
        }
        $this->date = $obj->date;
        $this->lastUpdatedMs = $obj->last_updated_ms;
        $this->notional30DayVolume = (string) $obj->notional_30d_volume;

        $days = [];
        array_walk($obj->notional_1d_volume, function(\stdClass $day) use (&$days) {
            $days[] = new OneDayVolume($day->date, (string) $day->notional_volume);
        });
        $this->notional1DayVolume = $days;

        foreach (array_keys($this->bps) as $bps) {
            $this->bps[$bps] = $obj->$bps;
        }
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getLastUpdatedMs(): int
    {
        return $this->lastUpdatedMs;
    }

    public function getApiAuctionFeeBPS(): int
    {
        return $this->bps['api_auction_fee_bps'];
    }

    public function getApiMakerFeeBPS(): int
    {
        return $this->bps['api_maker_fee_bps'];
    }

    public function getApiTakerFeeBPS(): int
    {
        return $this->bps['api_taker_fee_bps'];
    }

    public function getBlockMakerFeeBPS(): int
    {
        return $this->bps['block_maker_fee_bps'];
    }

    public function getBlockTakerFeeBPS(): int
    {
        return $this->bps['block_taker_fee_bps'];
    }

    public function getFixAuctionFeeBPS(): int
    {
        return $this->bps['fix_auction_fee_bps'];
    }

    public function getFixMakerFeeBPS(): int
    {
        return $this->bps['fix_maker_fee_bps'];
    }

    public function getFixTakerFeeBPS(): int
    {
        return $this->bps['fix_taker_fee_bps'];
    }

    public function getWebAuctionFeeBPS(): int
    {
        return $this->bps['web_auction_fee_bps'];
    }

    public function getWebMakerFeeBPS(): int
    {
        return $this->bps['web_maker_fee_bps'];
    }

    public function getWebTakerFeeBPS(): int
    {
        return $this->bps['web_taker_fee_bps'];
    }

    /**
     * @return OneDayVolume[]
     */
    public function getNotional1DayVolume(): array
    {
        return $this->notional1DayVolume;
    }

    public function getNotional30DayVolume(): string
    {
        return $this->notional30DayVolume;
    }

    public function jsonSerialize()
    {
        return [
            'date' => $this->date,
            'last_updated_ms' => $this->lastUpdatedMs,
            'notional_1d_volume' => $this->notional1DayVolume,
            'notional_30d_volume' => $this->notional30DayVolume,
            'api_auction_fee_bps' => $this->bps['api_auction_fee_bps'],
            'api_maker_fee_bps' => $this->bps['api_maker_fee_bps'],
            'api_taker_fee_bps' => $this->bps['api_taker_fee_bps'],
            'block_maker_fee_bps' => $this->bps['block_maker_fee_bps'],
            'block_taker_fee_bps' => $this->bps['block_taker_fee_bps'],
            'fix_auction_fee_bps' => $this->bps['fix_auction_fee_bps'],
            'fix_maker_fee_bps' => $this->bps['fix_maker_fee_bps'],
            'fix_taker_fee_bps' => $this->bps['fix_taker_fee_bps'],
            'web_auction_fee_bps' => $this->bps['web_auction_fee_bps'],
            'web_maker_fee_bps' => $this->bps['web_maker_fee_bps'],
            'web_taker_fee_bps' => $this->bps['web_taker_fee_bps'],
        ];
    }
}
