<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model;

final class GetRecordId implements GetRecordIdInterface
{
    public function get(string $clientOrderId): int
    {
        $id = \explode('_', $clientOrderId)[1] ?? '';
        if (ctype_digit($id)) {
            $id = (int) $id;
        }
        if (!is_int($id) || $id === 0 || strpos($clientOrderId, 'repeater_') !== 0) {
            throw new \InvalidArgumentException(sprintf(
                'Client Order ID "%s" does is not a valid TradeRepeater format.',
                $clientOrderId
            ));
        }
        return $id;
    }
}
