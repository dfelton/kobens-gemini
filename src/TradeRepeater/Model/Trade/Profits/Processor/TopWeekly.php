<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Processor;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Sql\Select;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\Exchange\Order\Maker\RequiredQuote;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;
use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Gemini\TradeRepeater\Exception\UnsupportedAddAmountStateException;

final class TopWeekly
{
    private AddAmount $addAmount;

    private TableGatewayInterface $tblStats;

    private RequiredQuote $requiredQuote;

    private EmergencyShutdownInterface $emergencyShutdown;

    public function __construct(
        AddAmount $addAmount,
        Adapter $adapter,
        RequiredQuote $requiredQuote,
        EmergencyShutdownInterface $emergencyShutdown
    ) {
        $this->addAmount = $addAmount;
        $this->requiredQuote = $requiredQuote;
        $this->emergencyShutdown = $emergencyShutdown;
        $this->tblStats = new TableGateway('repeater_stats_7day_aggregate', $adapter);
    }

    /**
     * Returns the remaining quote amount
     *
     * @param string $quoteAmount
     * @param string $use
     * @return string
     */
    public function execute(string $quoteAmount, string $use = '0.50'): string
    {
        $useAmount = Multiply::getResult($quoteAmount, $use);
        $remaining = $useAmount;
        foreach ($this->getTrades() as $trade) {
            $pair = Pair::getInstance($trade->getSymbol());
            $amountRequired = $this->requiredQuote->get(
                $trade->getBuyPrice(),
                Multiply::getResult($pair->getMinOrderIncrement(), '5')
            );
            if (Compare::getResult($amountRequired, $remaining) === Compare::LEFT_LESS_THAN) {
                try {
                    $amountAdded = $this->addAmount->add($trade->getId(), $pair->getMinOrderIncrement());
                } catch (UnsupportedAddAmountStateException $e) {
                    // Something else may have been working with it between time we selected from database and itereated to now
                    continue;
                } catch (\Exception $e) {
                    $this->emergencyShutdown->enableShutdownMode($e);
                    break;
                }
                if ($amountAdded !== '0') {
                    $remaining = Subtract::getResult($remaining, $amountRequired);
                }
            }
        }
        return Add::getResult(Subtract::getResult($quoteAmount, $useAmount), $remaining);
    }

    private function getTrades(): array
    {
        $trades = $this->tblStats->select(function(Select $select) {
           $select->reset(Select::COLUMNS);
           $select->order('count DESC');
           $select->join('trade_repeater', 'repeater_stats_7day_aggregate.repeater_id = trade_repeater.id', '*');
           $select->limit('500');
           $select->where('trade_repeater.status = "BUY_PLACED"');
        });
        $data = [];
        foreach ($trades as $trade) {
            $data[] = new Trade(
                (int) $trade->id,
                (int) $trade->is_enabled,
                (int) $trade->is_error,
                $trade->status,
                $trade->symbol,
                $trade->buy_amount,
                $trade->buy_price,
                $trade->sell_amount,
                $trade->sell_price,
                $trade->buy_client_order_id ?: null,
                ((int) $trade->buy_order_id) ?: null,
                $trade->sell_client_order_id ?: null,
                ((int) $trade->sell_order_id) ?: null,
                $trade->note ?: null,
                $trade->meta ?: null
            );
        }
        return $data;
    }
}
