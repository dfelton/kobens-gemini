<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Core\Exception\ConnectionException;
use Kobens\Gemini\Api\Param\Amount;
use Kobens\Gemini\Api\Param\ClientOrderId;
use Kobens\Gemini\Api\Param\Price;
use Kobens\Gemini\Api\Param\Side;
use Kobens\Gemini\Api\Param\Symbol;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder\ForceMaker;
use Kobens\Gemini\Exception\MaxIterationsException;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\DataResource\BuyReadyInterface;
use Kobens\Gemini\TradeRepeater\DataResource\BuySentInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Buyer extends Command
{
    protected static $defaultName = 'trade-repeater:buyer';

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    /**
     * @var BuyReadyInterface
     */
    private $buyReady;

    /**
     * @var BuySentInterface
     */
    private $buySent;

    /**
     * @var Side
     */
    private $side;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        BuyReadyInterface $buyReadyInterface,
        BuySentInterface $buySentInterface
    ) {
        $this->shutdown = $shutdownInterface;
        $this->buyReady = $buyReadyInterface;
        $this->buySent = $buySentInterface;
        $this->side = new Side('buy');
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Places buy orders on the exchange for the Gemini Trade Repeater');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while ($this->shutdown->isShutdownModeEnabled() === false) {
            try {
                $this->mainLoop($output);
                \sleep(1);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("<fg=red>Shutdown Signal Detected</>");
    }

    private function mainLoop(OutputInterface $output): void
    {
        foreach ($this->buyReady->getHealthyRecords() as $row) {
            if ($this->shutdown->isShutdownModeEnabled()) {
                break;
            }

            $clientOrderId = new ClientOrderId('repeater_'.$row->id.'_buy_'.\microtime(true));
            $this->buyReady->setNextState($row->id, $clientOrderId->getValue());

            try {
                $msg = $this->forceMaker($clientOrderId, $row->symbol, $row->buy_amount, $row->buy_price);
                $this->buySent->setNextState($row->id, $msg->order_id, $msg->price);
                $output->writeln(\sprintf(
                    "%s\t(%d) Buy Order ID %s placed on %s pair for amount of %s at rate of %s",
                    $this->now(), $row->id, $msg->order_id, $msg->symbol, $msg->original_amount, $msg->price
                ));
                if ($msg->price !== $row->buy_price) {
                    $output->writeln(\sprintf(
                        "%s\t\t<fg=yellow>(original buy price: %s)</>",
                        $this->now(), $row->buy_price
                    ));
                }
            } catch (ConnectionException $e) {
                $this->buySent->setErrorState($row->id, ConnectionException::class);
                $output->writeln("<fg=red>{$this->now()}\tConnection Exception Occurred.</>");

            } catch (MaxIterationsException $e) {
                $this->buyReady->resetState($row->id);
                $output->writeln(\sprintf(
                    "<fg=red>%s\tMax iterations reached for attempting ForceMaker on %s pair for price of %s.</>",
                    $this->now(), $row->symbol, $row->buy_price
                ));
            }
        }
    }

    private function forceMaker(ClientOrderId $clientOrderId, string $symbol, string $amount, string $price): \stdClass
    {
        $order = new ForceMaker($this->side, new Symbol(Pair::getInstance($symbol)), new Amount($amount), new Price($price), $clientOrderId);
        return \json_decode($order->getResponse()['body']);
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }

}
