<?php

namespace Kobens\Gemini\Api\Rest\DataModel;

final class Trade
{
    /**
     * @var string
     */
    private $symbol;

    /**
     * @var string
     */
    private $price;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var string
     */
    private $timestampms;

    /**
     * @var string
     */
    private $type;

    /**
     * @var boolean
     */
    private $aggressor;

    /**
     * @var string
     */
    private $fee_currency;

    /**
     * @var string
     */
    private $fee_amount;

    /**
     * @var string
     */
    private $tid;

    /**
     * @var string
     */
    private $order_id;

    /**
     * @var string|null
     */
    private $client_order_id;

    /**
     * @var string
     */
    private $exchange;

    /**
     * @var boolean
     */
    private $is_auction_fill;

    public function __construct(array $data, string $symbol)
    {
        $this->setData($data);
        $this->symbol = $symbol;
    }

    private function setData(array $data): void
    {
        $this->price = $data['price'];
        $this->amount = $data['amount'];
        $this->timestamp = $data['timestamp'];
        $this->timestampms = $data['timestampms'];
        $this->type = \strtolower($data['type']);
        $this->aggressor = $data['aggressor'];
        $this->fee_amount = $data['fee_amount'];
        $this->fee_currency = $data['fee_currency'];
        $this->tid = $data['tid'];
        $this->order_id = $data['order_id'];
        $this->client_order_id = \array_key_exists('client_order_id', $data) ? $data['client_order_id'] : null;
        $this->exchange = $data['exchange'];
        $this->is_auction_fill = $data['is_auction_fill'];
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getTimestampms(): string
    {
        return $this->timestampms;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function getAggressor(): bool
    {
        return $this->aggressor;
    }

    /**
     * @return string
     */
    public function getFeeAmount(): string
    {
        return $this->fee_amount;
    }

    /**
     * @return string
     */
    public function getFeeCurrency(): string
    {
        return $this->fee_currency;
    }

    /**
     * @return string
     */
    public function getTradeId(): string
    {
        return $this->tid;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * @return string|NULL
     */
    public function getClientOrderId(): ?string
    {
        return $this->client_order_id;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @return bool
     */
    public function getIsAuctionFill(): bool
    {
        return $this->is_auction_fill;
    }

}
