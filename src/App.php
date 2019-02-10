<?php

namespace Kobens\Gemini;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\{CancelAll as OrderCancelAll, NewOrder as OrderNew};

final class App
{
    /**
     * @var array
     */
    protected $actionClassMap = [
        OrderNew::API_ACTION_KEY => OrderNew::class,
        OrderCancelAll::API_ACTION_KEY => OrderCancelAll::class,
    ];
}