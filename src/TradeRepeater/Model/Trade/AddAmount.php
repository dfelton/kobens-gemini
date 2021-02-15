<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater\Model\Trade;

use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelOrderInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Exception\UnsupportedAddAmountStateException;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyPlaced as StatusBuyPlaced;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlaced as StatusSellPlaced;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\UpdateInterface;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Divide;
use Kobens\Math\BasicCalculator\Subtract;
use Kobens\Math\BasicCalculator\Multiply;
use Zend\Db\Adapter\Adapter;

final class AddAmount
{
    /**
     * Percentage. 1.00 == 1%
     *
     * @var string
     */
    private const BUY_THRESHOLD = '2.00';

    private CancelOrderInterface $cancelOrder;
    private GetPriceInterface $getPrice;
    private OrderStatusInterface $orderStatus;
    private TradeResource $tradeResource;
    private Adapter $adapter;
    private UpdateInterface $updateTrade;

    public function __construct(
        CancelOrderInterface $cancelOrder,
        GetPriceInterface $getPrice,
        OrderStatusInterface $orderStatus,
        TradeResource $tradeResource,
        Adapter $adapter,
        UpdateInterface $updateTrade
    ) {
        $this->cancelOrder = $cancelOrder;
        $this->getPrice = $getPrice;
        $this->orderStatus = $orderStatus;
        $this->tradeResource = $tradeResource;
        $this->adapter = $adapter;
        $this->updateTrade = $updateTrade;
    }

    public function add(int $id, string $amount): string
    {
        $trade = $this->tradeResource->getById($id);
        $pair = Pair::getInstance($trade->getSymbol());
        if (Compare::getResult($amount, $pair->getMinOrderIncrement()) === Compare::LEFT_LESS_THAN) {
            throw new \InvalidArgumentException(sprintf(
                'Add amount "%s" is invalid. The "%s" pair\'s minimum order increment is "%s"',
                $amount,
                strtoupper($pair->getSymbol()),
                $pair->getMinOrderIncrement()
            ));
        }
        $amountAdded = '0';
        switch ($trade->getStatus()) {
            case StatusBuyPlaced::STATUS_CURRENT:
                $order = $this->orderStatus->getStatus($trade->getBuyOrderId());
                if ($this->isBuyOrderReadyToAdd($trade, $order)) {
                    $amountAdded = $this->addToBuy(
                        $trade->getId(),
                        $amount,
                        $trade->getStatus(),
                        \Kobens\Core\Db::isInTransaction() === false
                    );
                }
                break;

            case StatusSellPlaced::STATUS_CURRENT:
//                 $order = $this->orderStatus->getStatus($trade->getSellOrderId());
//                 break;

            default:
                throw new UnsupportedAddAmountStateException(sprintf(
                    'Trade Repeater Record "%d" in unsupported state to add amount to. Current state "%s".',
                    $id,
                    $trade->getStatus()
                ));
        }
        return $amountAdded;
    }

    public function addTo(string $symbol, string $amount, string $priceFrom, string $priceTo): void
    {
        $rows = $this->tradeResource->getList(
            $symbol,
            [
                'buy_price_gte' => $priceFrom,
                'buy_price_lte' => $priceTo,
                'status' => 'BUY_PLACED',
            ]
        );
        /** @var Trade $row */
        foreach ($rows as $row) {
            $this->add($row->getId(), $amount);
        }
    }

    private function addToBuy(int $id, string $amount, string $expectedStatus, bool $useTransaction): string
    {
        if ($useTransaction) {
            $this->adapter->driver->getConnection()->beginTransaction();
        }
        $trade = $this->tradeResource->getById($id, true);
        $amountAdded = '0';
        try {
            if ($trade->getStatus() !== $expectedStatus && $useTransaction) {
                // Release lock on Trade record even though we cannot add to it.
                $this->adapter->driver->getConnection()->commit();
            } else {
                $order = $this->cancelOrder->cancel($trade->getBuyOrderId());
                if (
                    $order->executed_amount !== '0' ||
                    $order->is_cancelled !== true
                ) {
                    $this->setTradeIsError($order, $trade);
                } else {
                    $this->setTradeNewAmountBuyReady(
                        $trade,
                        $amount
                    );
                }
                if ($useTransaction) {
                    $this->adapter->driver->getConnection()->commit();
                }
                $amountAdded = $amount;
            }
        } catch (\Exception $e) {
            if ($useTransaction) {
                $this->adapter->driver->getConnection()->rollback();
            }
        }
        return $amountAdded;
    }

    private function setTradeNewAmountBuyReady(Trade $trade, string $amount): void
    {
        $this->updateTrade->execute(new Trade(
            $trade->getId(),
            $trade->isEnabled(),
            $trade->isError(),
            'BUY_READY',
            $trade->getSymbol(),
            Add::getResult($trade->getBuyAmount(), $amount),
            $trade->getBuyPrice(),
            Add::getResult($trade->getSellAmount(), $amount),
            $trade->getSellPrice(),
            null,
            null,
            null,
            null,
            null,
            null,
        ));
    }

    private function setTradeIsError(\stdClass $cancelResponse, Trade $trade): void
    {
        $this->updateTrade->execute(new Trade(
            $trade->isEnabled(),
            1,
            'ERR_ADD_TO',
            $trade->getSymbol(),
            $trade->getBuyAmount(),
            $trade->getBuyPrice(),
            $trade->getSellAmount(),
            $trade->getSellPrice(),
            $trade->getBuyClientOrderId(),
            $trade->getBuyOrderId(),
            $trade->getSellClientOrderId(),
            $trade->getSellOrderId(),
            'An unexepected result occurred while cancelling an order for an Add Amount request.',
            json_encode([
                'original_meta' => $trade->getMeta(),
                'original_staus' => $trade->getStatus(),
                'cancel_order_json' => json_encode($cancelResponse),
            ]),
        ));
    }

    /**
     * For a buy order to be "ready" to add amount to it, it must
     * - Have executed "0" amount
     *     - Intended to keep it simple for adding to.
     * - Spread between original buy price and current prices must be over a certain threshold
     *     - Intended to prevent mistakes when prices are volatile.
     *
     * @param Trade $trade
     * @param \stdClass $order
     * @return bool
     */
    private function isBuyOrderReadyToAdd(Trade $trade, \stdClass $order): bool
    {
        if ($order->executed_amount !== '0') {
            return false;
        }

        $marketBid = $this->getPrice->getBid($trade->getSymbol());
        $spread = Subtract::getResult($marketBid, $trade->getBuyPrice());
        $safeToAdd = false;

        if (Compare::getResult($trade->getBuyPrice(), $spread) === Compare::LEFT_LESS_THAN) {
            // If the spread is higher than the bid price,
            // then the market already over 100% higher than original bid
            $safeToAdd = true;
        } else {
            // Is current market bid a safe threshold percentage higher than original bid
            $percent = Multiply::getResult(
                Divide::getResult($trade->getBuyPrice(), $spread, 4),
                '100'
            );
            if (Compare::getResult($percent, self::BUY_THRESHOLD) === Compare::LEFT_GREATER_THAN) {
                $safeToAdd = true;
            }
        }
        return $safeToAdd;
    }

    private function isSellOrderReadyToAdd(Trade $trade, \stdClass $order): bool
    {
    }
}
