<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Gemini\Exception;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\Balance;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface;

final class GetNotionalBalances implements GetNotionalBalancesInterface
{
    private const URL_PATH = '/v1/notionalbalances/usd';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    /**
     * @return BalanceInterface[]
     */
    public function getBalances(): array
    {
        $response = $this->request->getResponse(self::URL_PATH, [], [], true);
        $balances = [];
        foreach (json_decode($response->getBody()) as $c) {
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
        $response = $this->request->getResponse(self::URL_PATH, [], [], true);
        $currency = strtoupper($currency);
        $arr = json_decode($response->getBody());
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
}
