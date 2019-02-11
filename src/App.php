<?php

namespace Kobens\Gemini;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\{CancelAll as OrderCancelAll, NewOrder as OrderNew};

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
        ];
    }
}