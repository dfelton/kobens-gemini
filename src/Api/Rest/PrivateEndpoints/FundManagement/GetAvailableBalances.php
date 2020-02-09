<?php

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\AbstractPrivateRequest;

class GetAvailableBalances extends AbstractPrivateRequest implements GetAvailableBalancesInterface
{
    private const URL_PATH = '/v1/balances';

    public function getCurrency(string $currency): \stdClass
    {
        $balances = \json_decode($this->getResponse()['body']);
        foreach ($balances as $balance) {
            if (\strtolower($balance->currency) === \strtolower($currency)) {
                return $balance;
            }
        }
        throw new \Exception('No balance for '.$currency);
    }

    public function getBalances(): array
    {
        return \json_decode($this->getResponse()['body']);
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
