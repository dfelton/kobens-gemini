<?php

namespace Kobens\Gemini\Api\Rest\Request\Order\Placement;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Param\{Side, Symbol, Amount, Price, ClientOrderId};
use Kobens\Gemini\Api\Rest\Request;
use Kobens\Gemini\Exception\Api\InsufficientFundsException;
use Kobens\Gemini\Exchange;
use Kobens\Exchange\Exception\Order\MakerOrCancelWouldTakeException;

class NewOrder extends Request
{
    const REQUEST_URI = '/v1/order/new';

    protected $defaultPayload = [
        'type' => 'exchange limit',
//         'options' => ['maker-or-cancel'],
    ];

    public function __construct(
        Side $side,
        Symbol $symbol,
        Amount $amount,
        Price $price,
        ClientOrderId $clientOrderId
    ) {
        parent::__construct();

        $pair = (new Exchange())->getPair($symbol->getValue());

        $this->validateAmount($amount->getValue(), $pair);
        $this->validatePrice($price->getValue(), $pair);

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

    protected function validateAmount(string $amount, PairInterface $pair) : void
    {
        $min = $pair->getMinOrderSize();
        if ($amount < $min) {
            throw new \Exception(\sprintf(
                'Invalid amount "%s", min allowed for the "%s" pair on "%s" is "%s".',
                $amount,
                $pair->getPairSymbol(),
                (string) (new Host()),
                $min
            ));
        }
        unset($min);

        $parts = \explode('.', $amount);
        if (isset($parts[1])) {
            $length = \strlen($parts[1]);
            $min = $pair->getMinOrderIncrement();
            $minParts = explode('.', $min);
            if (isset($minParts[1])) {
                $maxPrecision = \strlen($minParts[1]);
                if ($length > $maxPrecision) {
                    throw new \Exception(\sprintf(
                        'Invalid amount precision "%s", min increment allowed is "%s"',
                        $length,
                        $pair->getMinOrderIncrement()
                    ));
                }
            } else {
                throw new \Exception(\sprintf(
                    'Invalid amount precision "%s", min increment allowed is "%s"',
                    $length,
                    $pair->getMinOrderIncrement()
                ));
            }
        }
    }

    protected function validatePrice(string $price, PairInterface $pair) : void
    {
        $min = $pair->getMinPriceIncrement();
        if ($price < $min) {
            throw new \Exception(\sprintf(
                'Invalid price "%s", min price is "%s".',
                $price,
                $min
            ));
        }
        $parts = \explode('.', $price);
        if (isset($parts[1])) {
            if (trim($parts[1], 0) !== '') {
                $orderIncrement = '0.' .$parts[1];
                if ($orderIncrement < $pair->getMinOrderIncrement()) {
                    throw new \Exception(\sprintf(
                        'Invalid price precision "%s", min increment allowed is "%s"',
                        $orderIncrement,
                        $pair->getMinOrderIncrement()
                    ));
                }
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