<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

interface GetAvailableBalancesInterface
{
    public function getBalances(): array;

    public function getCurrency(string $currency): \stdClass;
}