<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\TradeRepeater\DataResource\{BuyFilled, SellSent};
use Kobens\Gemini\Api\Param\{Side, Symbol, Amount, Price, ClientOrderId};
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder\ForceMaker;
use Kobens\Gemini\Exception\MaxIterationsException;

final class Seller extends Command
{
    protected static $defaultName = 'kobens:gemini:trade-repeater:seller';

    protected function configure()
    {
        $this->setDescription('Places sell orders on the exchange for the Gemini Trade Repeater');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buyFilled = new BuyFilled();
        $sellSent = new SellSent();

        $loop = true;
        while ($loop) {
            try {
                foreach ($buyFilled->getHealthyRecords() as $row) {
                    $sellClientOrderId = 'repeater_'.$row->id.'_sell_'.\time();
                    $buyFilled->setNextState($row->id, ['sell_client_order_id' => $sellClientOrderId]);

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
                        $output->writeln(\sprintf(
                            "<fg=red>%s\tMax iterations reached for attempting ForceMaker on %s pair for price of %s.</>",
                            (new \DateTime())->format('Y-m-d H:i:s'),
                            $row->symbol,
                            $row->sell_price
                        ));
                        $output->writeln(\sprintf(
                            "<fg=red>%s\tSleeping 5 seconds...</>"
                            (new \DateTime())->format('Y-m-d H:i:s')
                        ));
                        \sleep(5);

                        // we'll pick it up again the next iteration
                        $buyFilled->resetState($row->id);
                        continue;
                    }
                    $msg = \json_decode($response['body']);
                    if ($response['code'] === 200 && $msg->order_id) {
                        $sellSent->setNextState($row->id, ['sell_order_id' => $msg->order_id, 'sell_json' => $response['body']]);
                        $output->writeln(\sprintf(
                            "%s\tSell Order ID %s placed on %s pair for amount of %s at rate of %s",
                            (new \DateTime())->format('Y-m-d H:i:s'),
                            $msg->order_id,
                            $msg->symbol,
                            $msg->original_amount,
                            $msg->price
                        ));
                        if ($msg->price !== $row->sell_price) {
                            $output->writeln(\sprintf(
                                "%s\t\t<fg=yellow>(original sell price: %s)</>",
                                (new \DateTime())->format('Y-m-d H:i:s'),
                                $row->sell_price
                            ));
                        }
                    }
                }
            } catch (\Exception $e) {
                \Zend\Debug\Debug::dump(
                    [
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                        'class' => \get_class($e),
                        'trace' => $e->getTraceAsString()
                    ],
                    (new \DateTime())->format('Y-m-d H:i:s')."\tUnhandled Exception"
                );
                $loop = false;
            }
            \sleep(1);
        }
    }

}
