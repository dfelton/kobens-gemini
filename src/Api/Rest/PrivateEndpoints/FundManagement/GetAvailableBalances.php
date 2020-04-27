<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances\Balance;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances\BalanceInterface;

class GetAvailableBalances extends AbstractPrivateRequest implements GetAvailableBalancesInterface
{
    private const URL_PATH = '/v1/balances';

    public function getBalance(string $currency): BalanceInterface
    {
        foreach ($this->getBalances() as $key => $balance) {
            if (strtolower($currency) === strtolower($key)) {
                return $balance;
            }
        }
        throw new \Exception('No balance for '.$currency);
    }

    /**
     * @return BalanceInterface[]
     */
    public function getBalances(): array
    {
        $balances = [];
        /** @var \stdClass $b */
        foreach (\json_decode($this->getResponse()->getBody()) as $b) {
            $balances[$b->currency] = new Balance($b->amount, $b->available, $b->availableForWithdrawal, $b->currency);
        }
        return $balances;
    }

    protected function getUrlPath(): string
    {
        return self::URL_PATH;
    }

    protected function getPayload(): array
    {
        return [];
    }
}
