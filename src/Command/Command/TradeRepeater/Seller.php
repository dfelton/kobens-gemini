<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Gemini\Api\Param\Amount;
use Kobens\Gemini\Api\Param\ClientOrderId;
use Kobens\Gemini\Api\Param\Price;
use Kobens\Gemini\Api\Param\Side;
use Kobens\Gemini\Api\Param\Symbol;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder\ForceMaker;
use Kobens\Gemini\Exception\MaxIterationsException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\DataResource\BuyFilledInterface;
use Kobens\Gemini\TradeRepeater\DataResource\SellSentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Seller extends Command
{
    protected static $defaultName = 'trade-repeater:seller';

    /**
     * @var BuyFilledInterface
     */
    private $buyFilled;

    /**
     * @var SellSentInterface
     */
    private $sellSent;

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    public function __construct(
        BuyFilledInterface $buyFilledInterface,
        SellSentInterface $sellSentInterface,
        EmergencyShutdownInterface $shutdownInterface
    ) {
        parent::__construct();
        $this->buyFilled = $buyFilledInterface;
        $this->sellSent = $sellSentInterface;
        $this->shutdownInterface = $shutdownInterface;
    }

    protected function configure()
    {
        $this->setDescription('Places sell orders on the exchange for the Gemini Trade Repeater');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                $this->mainLoop($output);
                \sleep(1);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode(\json_encode([
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'class' => \get_class($e),
                    'trace' => $e->getTraceAsString()
                ]));
            }
        }
        $output->writeln("<fg=red>Shutdown Signal Detected</>");
    }

    private function mainLoop(OutputInterface $output): void
    {
        foreach ($this->buyFilled->getHealthyRecords() as $row) {
            $sellClientOrderId = 'repeater_'.$row->id.'_sell_'.\microtime(true);
            $this->buyFilled->setNextState($row->id, ['sell_client_order_id' => $sellClientOrderId]);

            $order = new ForceMaker(
                new Side('sell'),
                new Symbol(Pair::getInstance($row->symbol)),
                new Amount($row->sell_amount),
                new Price($row->sell_price),
                new ClientOrderId($sellClientOrderId)
            );

            try {
                $response = $order->getResponse();

                // TODO Kobens\Core\Exception\ConnectionException
                // TODO Lots of other exception types.
            } catch (MaxIterationsException $e) {
                // there must be a lot of buying going on right this moment
                $this->buyFilled->resetState($row->id);
                $output->writeln(\sprintf(
                    "<fg=red>%s\tMax iterations reached for attempting ForceMaker on %s pair for price of %s.</>",
                    $this->now(),
                    $row->symbol,
                    $row->sell_price
                ));
                $output->writeln(\sprintf(
                    "<fg=red>%s\tSleeping 5 seconds...</>",
                    $this->now()
                ));
                \sleep(5);

                // we'll pick it up again the next iteration
                continue;
            }
            $msg = \json_decode($response['body']);
            if ($response['code'] === 200 && $msg->order_id) {
                $this->sellSent->setNextState($row->id, $msg->order_id, $msg->price);
                $output->writeln(\sprintf(
                    "%s\t(%d) Sell Order ID %s placed on %s pair for amount of %s at rate of %s",
                    $this->now(),
                    $row->id,
                    $msg->order_id,
                    $msg->symbol,
                    $msg->original_amount,
                    $msg->price
                ));
                if ($msg->price !== $row->sell_price) {
                    $output->writeln(\sprintf(
                        "%s\t\t<fg=yellow>(original sell price: %s)</>",
                        $this->now(), $row->sell_price
                    ));
                }
            }
        }
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}
