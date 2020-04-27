<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

use Kobens\Gemini\Exception;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\Balance;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;

final class GetNotionalBalances extends AbstractPrivateRequest implements GetNotionalBalancesInterface
{
    /**
     * @return BalanceInterface[]
     */
    public function getBalances(): array
    {
        $balances = [];
        foreach (json_decode($this->getResponse()->getBody()) as $c) {
            $balances[$c->currency] = new Balance(
                $c->currency,
                $c->amount,
                $c->amountNotional,
                $c->available,
                $c->availableNotional,
                $c->availableForWithdrawal,
                $c->availableForWithdrawalNotional
            );
        }
        return $balances;
    }

    public function getBalance(string $currency): BalanceInterface
    {
        $currency = strtoupper($currency);
        $arr = json_decode($this->getResponse()->getBody());
        /** @var \stdClass $c */
        foreach ($arr as $c) {
            if ($currency === $c->currency) {
                return new Balance(
                    $c->currency,
                    $c->amount,
                    $c->amountNotional,
                    $c->available,
                    $c->availableNotional,
                    $c->availableForWithdrawal,
                    $c->availableForWithdrawalNotional
                );
            }
        }
        throw new Exception("No Currency '%s' found.");
    }

    protected function getUrlPath(): string
    {
        return '/v1/notionalbalances/usd';
    }

    protected function getPayload(): array
    {
        return [];
    }
}
