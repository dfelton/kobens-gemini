<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\RequestInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances\Balance;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances\BalanceInterface;

class GetAvailableBalances implements GetAvailableBalancesInterface
{
    private const URL_PATH = '/v1/balances';

    private RequestInterface $request;

    public function __construct(
        RequestInterface $requestInterface
    ) {
        $this->request = $requestInterface;
    }

    public function getBalance(string $currency): BalanceInterface
    {
        foreach ($this->getBalances() as $key => $balance) {
            if (strtolower($currency) === strtolower($key)) {
                return $balance;
            }
        }
        throw new \Exception('No balance for ' . $currency);
    }

    /**
     * @return BalanceInterface[]
     */
    public function getBalances(): array
    {
        $response = $this->request->getResponse(self::URL_PATH, [], [], true);
        $balances = [];
        /** @var \stdClass $b */
        foreach (\json_decode($response->getBody()) as $b) {
            $balances[$b->currency] = new Balance($b->currency, $b->amount, $b->available, $b->availableForWithdrawal);
        }
        return $balances;
    }
}
