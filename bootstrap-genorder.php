<?php
/**
 * Bootstrap file for /genorder-*.php files
 */

require __DIR__.'/bootstrap.php';

use Kobens\Core\Config;
use Kobens\Core\Http\Request\Throttler;
use Kobens\Core\Http\Request\Throttler\Adapter\MariaDb;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FundManagement\GetAvailableBalances;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\NewOrder\MakerOrCancel;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\PricePointGenerator;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\Request;

class GenOrder
{
    private MakerOrCancel $makerOrCancel;

    private GetAvailableBalances $getAvailableBalances;

    private Pair $pair;

    public function __construct(string $pairSymbol)
    {
        $this->pair = Pair::getInstance($pairSymbol);

        $request = $this->getRequest();
        $this->makerOrCancel = new MakerOrCancel($request);
        $this->getAvailableBalances = new GetAvailableBalances($request);
    }

    private function getRequest(): Request
    {
        $config = Config::getInstance();

        $loggerCurl = new \Monolog\Logger('curl');
        $loggerCurl->pushHandler(new \Monolog\Handler\StreamHandler($config->getLogDir() . '/curl.log'));

        $loggerRequestPrivate = new \Monolog\Logger('request.private');
        $loggerRequestPrivate->pushHandler(new \Monolog\Handler\StreamHandler($config->getLogDir() . '/request.private.log'));

        $hostInterface = new Host($config->get('gemini')->api->host);
        $privateThrottlerInterface = new Throttler(
            new MariaDb(new \Zend\Db\Adapter\Adapter($config->get('kobens')->core->throttler->adapter->mariadb->toArray())),
            $hostInterface->getHost().'::private'
        );
        $keyInterface = new Key(
            $config->get('gemini')->api->key->public_key,
            $config->get('gemini')->api->key->secret_key
        );
        $nonceInterface = new Nonce();
        return new Request(
            $hostInterface,
            $privateThrottlerInterface,
            $keyInterface,
            $nonceInterface,
            new \Kobens\Core\Http\Curl($loggerCurl),
            $loggerRequestPrivate
        );
    }

    /**
     *
     * @param string $buy           Amount of base currency to buy
     * @param string $save          Amount of base currency to sell
     * @param string $start         Quote currency start price
     * @param string $end           Quote currency end price
     * @param string $increment     Quote currency increment per order placement point. 1 means 0% change in price. 1.05 means 5% increase, etc.
     * @param string $sellAfterGain Increase in price of quote currency for sell order
     * @param string $action        Action to take ("buy" or "sell", else debug output)
     */
    public function generate(
        string $buy,
        string $save,
        string $start,
        string $end,
        string $increment,
        string $sellAfterGain,
        string $action = null
    ): void
    {
        $result = PricePointGenerator::get($this->pair, $buy, $start, $end, $increment, $sellAfterGain, $save, true);

        $base = $this->pair->getBase();
        $quote = $this->pair->getQuote();

        $orders = $result->getPricePoints();

        $outputDebug = true;
        if ($action === 'buy' || $action === 'sell') {
            $outputDebug = false;
            $funds = $this->getAvailableBalances->getBalance(
                ($action === 'buy' ? $this->pair->getQuote()->getSymbol() : $base->getSymbol())
            );

            $amountRequired = $action === 'buy'
                ? Add::getResult($result->getTotalBuyQuote(), $result->getTotalBuyFees())
                : $result->getTotalSellBase();

            if (Compare::getResult('0', Subtract::getResult($funds->getAvailable(), $amountRequired)) === Compare::LEFT_GREATER_THAN) {
                echo "\nInsufficient funds for order(s).\nRequired: {$amountRequired}\nAvailable: {$funds->getAvailable()}\n";
                exit(1);
            }

            if ($action === 'sell') {
                $orders = \array_reverse($orders);
            }

            for ($i = 0, $j = \count($orders); $i < $j; $i++) {
                $clientOrderId = 'trade_repeater_' . $action . '_' . $this->pair->getSymbol() . ((string) microtime(true));
                $r = $this->makerOrCancel->place(
                    $this->pair,
                    $action,
                    $action === 'buy' ? $orders[$i]->getBuyAmountBase() : $orders[$i]->getSellAmountBase(),
                    $action === 'buy' ? $orders[$i]->getBuyPrice() : $orders[$i]->getSellPrice(),
                    $clientOrderId
                );
                if ($r->is_live) {
                    echo "order {$r->order_id} {$r->side} {$r->symbol} {$r->original_amount} @ {$r->price}\n";
                } else {
                    \Zend\Debug\Debug::dump($r, 'Order Not Live:');
                    exit(1);
                }
            }
        }

        if (!$outputDebug) {
            exit(0);
        }

        /** @var PricePoint $first */
        $first = $action === 'sell' ? \end($orders) : \reset($orders);

        /** @var PricePoint $last */
        $last  = $action === 'sell' ? \reset($orders) : \end($orders);

        \Zend\Debug\Debug::dump(
            [
                'order_first' => !$first instanceof PricePoint ? null : [
                    'buy_price' => $first->getBuyPrice(),
                    "buy_amount_{$base->getSymbol()}" => $first->getBuyAmountBase(),
                    "buy_amount_{$quote->getSymbol()}" => $first->getBuyAmountQuote(),
                    "buy_fee" => $first->getBuyFee(),
                    "buy_fee_hold" => $first->getBuyFeeHold(),
                    "sell_price" => $first->getSellPrice(),
                    "sell_amount_{$base->getSymbol()}" => $first->getSellAmountBase(),
                    "sell_amount_{$quote->getSymbol()}" => $first->getSellAmountQuote(),
                    "sell_fee" => $first->getSellFee(),
                    "profit_{$base->getSymbol()}" => $first->getProfitBase(),
                    "profit_{$quote->getSymbol()}" => $first->getProfitQuote(),
                ],
                'order_last'  => ! $last instanceof PricePoint ? null : [
                    'buy_price' => $last->getBuyPrice(),
                    "buy_amount_{$base->getSymbol()}" => $last->getBuyAmountBase(),
                    "buy_amount_{$quote->getSymbol()}" => $last->getBuyAmountQuote(),
                    "buy_fee" => $last->getBuyFee(),
                    "buy_fee_hold" => $last->getBuyFeeHold(),
                    "sell_price" => $last->getSellPrice(),
                    "sell_amount_{$base->getSymbol()}" => $last->getSellAmountBase(),
                    "sell_amount_{$quote->getSymbol()}" => $last->getSellAmountQuote(),
                    "sell_fee" => $last->getSellFee(),
                    "profit_{$base->getSymbol()}" => $last->getProfitBase(),
                    "profit_{$quote->getSymbol()}" => $last->getProfitQuote(),
                ],
                'order_count' => \count($orders),
                "buy_{$base->getSymbol()}" => $result->getTotalBuyBase(),
                "buy_{$quote->getSymbol()}_hold" => Add::getResult($result->getTotalBuyFeesHold(), $result->getTotalBuyQuote()),
                "sell_{$base->getSymbol()}" => $result->getTotalSellBase(),
                "total_profit_{$quote->getSymbol()}" => $result->getTotalProfitQuote(),
                "total_profit_{$base->getSymbol()}" => $result->getTotalProfitBase(),
            ],
            'Summary'
        );
    }
}
