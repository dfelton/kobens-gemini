<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\CliMonitor\Helper;

use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Market\GetPrice\Result;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalancesInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Gemini\Exchange\Currency\Pair;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGatewayInterface;

final class Data implements DataInterface
{
    /**
     * @var Result[]
     */
    private array $priceResult = [];

    /**
     * @var \stdClass[]|null
     */
    private ?array $orders = null;

    /**
     * @var \Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface[]
     */
    private ?array $notionalBalances = null;

    private GetActiveOrdersInterface $activeOrders;

    private GetNotionalBalancesInterface $notionalBalance;

    private GetPriceInterface $price;

    private TableGatewayInterface $tblTradeRepeater;

    private Adapter $adapter;

    public function __construct(
        GetActiveOrdersInterface $getActiveOrdersInterface,
        GetNotionalBalancesInterface $getNotionalBalancesInterface,
        GetPriceInterface $getPriceInterface,
        TableGatewayInterface $tblTradeRepeater,
        Adapter $adapter
    ) {
        $this->activeOrders = $getActiveOrdersInterface;
        $this->notionalBalance = $getNotionalBalancesInterface;
        $this->price = $getPriceInterface;
        $this->tblTradeRepeater = $tblTradeRepeater;
        $this->adapter = $adapter;
    }

    public function getProfitsBucketValue(string $bucket): string
    {
        $stmt = $this->adapter->query('SELECT `amount` FROM `repeater_profits_bucket` WHERE `currency` = :bucket');
        $result = $stmt->execute(['bucket' => $bucket]);
        return $result->count() === 1 ? $result->current()['amount'] : '';
    }

    public function reset(): void
    {
        $this->priceResult = [];
        $this->orders = null;
        $this->notionalBalances = null;
    }

    public function getPriceResult(string $symbol): Result
    {
        if (!($this->priceResult[$symbol] ?? null)) {
            $this->priceResult[$symbol] = $this->price->getResult($symbol);
        }
        return $this->priceResult[$symbol];
    }

    /**
     * @return \stdClass[]
     */
    public function getOrdersData(): array
    {
        if ($this->orders === null) {
            $this->orders = $this->activeOrders->getOrders();
        }
        return $this->orders;
    }

    /**
     * @return \Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetNotionalBalances\BalanceInterface[]
     */
    public function getNotionalBalances(): array
    {
        if ($this->notionalBalances === null) {
            $this->notionalBalances = $this->notionalBalance->getBalances();
        }
        return $this->notionalBalances;
    }

    public function getNotional(string $symbol, string $amount): string
    {
        return Multiply::getResult(
            $this->getPriceResult($symbol)->getBid(),
            $amount
        );
    }

    public function getExtra(): array
    {
        $records = $this->tblTradeRepeater->select(function (Select $select) {
            $select->columns(['symbol', 'status', 'buy_price', 'buy_amount', 'meta']);
            $select->where('is_enabled = 1');
        });
        $data = [];
        $longestDecimal = 0;
        $longestWhole = 0;
        $usdMakerDeposit = '0';
        $totalUsdInvestment = '0';
        foreach ($records as $record) {
            $pair = Pair::getInstance($record->symbol);
            if ($pair->getQuote()->getSymbol() === 'usd') {
                if (($data[$pair->getSymbol()] ?? null) === null) {
                    $data[$pair->getSymbol()] = '0';
                }
                $buyPrice = $record->buy_price;
                if ($record->meta !== null) {
                    $meta = json_decode($record->meta);
                    if ($meta->buy_price ?? null) {
                        $buyPrice = $meta->buy_price;
                    }
                }
                $costBasis = Multiply::getResult($buyPrice, $record->buy_amount);
                $makerDeposit = Multiply::getResult($costBasis, '0.0035'); // TODO: Reference constant
                $total = Add::getResult($costBasis, $makerDeposit);
                $totalUsdInvestment = Add::getResult($totalUsdInvestment, $total);

                $data[$pair->getSymbol()] = Add::getResult($data[$pair->getSymbol()], $total);

                // If the record is in a BUY_PLACED status, the funds for the maker deposit is already accounted for in the order
                if ($record->status !== 'BUY_PLACED') {
                    $usdMakerDeposit = Add::getResult($usdMakerDeposit, $makerDeposit);
                }

                $strlen = strlen(explode('.', $data[$pair->getSymbol()])[1] ?? '');
                if ($strlen > $longestDecimal) {
                    $longestDecimal = $strlen;
                }
                $strlen = strlen(explode('.', $data[$pair->getSymbol()])[0]);
                if ($strlen > $longestWhole) {
                    $longestWhole = $strlen;
                }
            }
        }
        ksort($data);
        $strlen = strlen(explode('.', $usdMakerDeposit)[0]);
        if ($strlen > $longestWhole) {
            $longestWhole = $strlen;
        }
        $strlen = strlen(explode('.', $usdMakerDeposit)[1] ?? '');
        if ($strlen > $longestDecimal) {
            $longestDecimal = $strlen;
        }

        $strlen = strlen(explode('.', $totalUsdInvestment)[0]);
        if ($strlen > $longestWhole) {
            $longestWhole = $strlen;
        }
        $strlen = strlen(explode('.', $totalUsdInvestment)[1] ?? '');
        if ($strlen > $longestDecimal) {
            $longestDecimal = $strlen;
        }
        $data['usd_maker_deposit'] = $usdMakerDeposit;
        $data['total_usd_investment'] = $totalUsdInvestment;

        foreach ($data as &$row) {
            $val = explode('.', bcadd($row, '0', $longestDecimal));
            $val[0] = str_pad($val[0], $longestWhole, ' ', STR_PAD_LEFT);
            $row = implode('.', $val);
        }

        return $data;
    }
}
