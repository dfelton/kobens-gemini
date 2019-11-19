<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

interface GetAvailableBalancesInterface
{
    public function getBalances(): array;

    public function getCurrency(string $currency): \stdClass;
}