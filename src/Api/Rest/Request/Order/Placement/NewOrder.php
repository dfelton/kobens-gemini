<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Param\{Side, Symbol, Amount, Price, ClientOrderId};
use Kobens\Gemini\Api\Rest\Request;
use Kobens\Gemini\Exception\Api\InsufficientFundsException;
use Kobens\Gemini\Exchange;
use Kobens\Exchange\Exception\Order\MakerOrCancelWouldTakeException;
use Kobens\Gemini\Exception\Exception;

class NewOrder extends Request
{
    const REQUEST_URI = '/v1/order/new';

    protected $defaultPayload = [
        'type' => 'exchange limit',
//         'options' => ['maker-or-cancel'],
    ];

    /**
     * @var PairInterface
     */
    private $pair;

    public function __construct(
        Side $side,
        Symbol $symbol,
        Amount $amount,
        Price $price,
        ClientOrderId $clientOrderId
    ) {
        parent::__construct();

        $this->pair = (new Exchange())->getPair($symbol->getValue());

        $this->validateAmount($amount->getValue());
        $this->validatePrice($price->getValue());

        $params = [
            'symbol' => $symbol->getValue(),
            'amount' => $amount->getValue(),
            'price'  => $price->getValue(),
            'side'   => $side->getValue(),
        ];
        if ($clientOrderId->getValue()) {
            $params['client_order_id'] = $clientOrderId->getValue();
        }
        $this->payload = \array_merge($this->defaultPayload, $params);
    }

    // @todo the approach to these setters are kinda gross. lets maybe slim down the constructor and rethink things in the setters

    public function setClientOrderId(ClientOrderId $clientOrderId): void
    {
        $this->payload = \array_merge($this->payload, ['client_order_id' => $clientOrderId->getValue()]);
    }

    public function setPrice(Price $price): void
    {
        $this->payload = \array_merge($this->payload, ['price' => $price->getValue()]);
    }

    public function setAmount(Amount $amount): void
    {
        $this->validateAmount($amount);
        $this->payload = \array_merge($this->payload, ['amount' => $amount->getValue()]);
    }

    protected function validateAmount(string $amount) : void
    {
        if ($amount < $this->pair->minOrderSize) {
            throw new Exception(\sprintf(
                'Invalid amount "%s", min allowed for the "%s" pair on "%s" is "%s".',
                $amount,
                $this->pair->symbol,
                (string) (new Host()),
                $this->pair->minOrderSize
            ));
        }

        $parts = \explode('.', $amount);
        if (isset($parts[1])) {
            $length = \strlen($parts[1]);
            $minParts = explode('.', $this->pair->minOrderIncrement);
            if (isset($minParts[1])) {
                $maxPrecision = \strlen($minParts[1]);
                if ($length > $maxPrecision) {
                    throw new \Exception(\sprintf(
                        'Invalid amount precision "%s", min increment allowed is "%s"',
                        $length,
                        $this->pair->minOrderIncrement
                    ));
                }
            } else {
                throw new \Exception(\sprintf(
                    'Invalid amount precision "%s", min increment allowed is "%s"',
                    $length,
                    $this->pair->minOrderIncrement
                ));
            }
        }
    }

    protected function validatePrice(string $price) : void
    {
        if ($price < $this->pair->minPriceIncrement) {
            throw new \Exception(\sprintf(
                'Invalid price "%s", min price is "%s".',
                $price,
                $this->pair->minPriceIncrement
            ));
        }
        $parts = \explode('.', $price);
        if (isset($parts[1]) && trim($parts[1], 0) !== '') {
            $priceIncrement = '0.' .$parts[1];
            if ($priceIncrement < $this->pair->minPriceIncrement) {
                throw new \Exception(\sprintf(
                    'Invalid price precision "%s", min increment allowed is "%s"',
                    $priceIncrement,
                    $this->pair->minPriceIncrement
                ));
            }
        }
    }

    protected function throwResponseException($response, $responseCode) : void
    {
        parent::throwResponseException($response, $responseCode);
        $obj = \json_decode($response);
        // @todo narrow this down to a single ==== comparison
        if ($responseCode >= 400 && $responseCode < 500) {
            if ($obj->reason === InsufficientFundsException::REASON) {
                throw new InsufficientFundsException($obj->message, $responseCode);
            }
        } elseif ($obj->is_cancelled) {
            if ($obj->reason === 'MakerOrCancelWouldTake') {
                throw new MakerOrCancelWouldTakeException($response, $responseCode);
            }
        }
    }
}