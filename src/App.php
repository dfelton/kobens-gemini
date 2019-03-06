<?php

namespace Kobens\Gemini;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\CancelAll as OrderCancelAll;
use Kobens\Gemini\App\Actions\MarketData\BookKeeper;
use Kobens\Gemini\App\Actions\Order\NewOrder as OrderNew;

final class App extends \Kobens\Core\App
{
    protected function getAvailableActions() : array
    {
        return [
            OrderNew::API_ACTION_KEY => [
                'description' => 'Place a new buy or sell order.',
                'class' => OrderNew::class,
            ],
            OrderCancelAll::API_ACTION_KEY => [
                'description' => 'Cancel all active orders.',
                'class' => OrderCancelAll::class,
            ],
            BookKeeper::API_ACTION_KEY => [
                'description' => 'Maintains in local cache a copy of the exchange market\'s order book.',
                'class' => BookKeeper::class
            ]
        ];
    }
}