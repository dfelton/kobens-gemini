<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\TradeRepeater\DataResource\{BuyReady, BuySent};
use Kobens\Gemini\Api\Param\{Side, Symbol, Amount, Price, ClientOrderId};
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder\ForceMaker;

final class Buyer extends Command
{
    protected static $defaultName = 'kobens:gemini:trade-repeater:buyer';

    protected function configure()
    {
        $this->setDescription('Places buy orders on the exchange for the Gemini Trade Repeater');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buyReady = new BuyReady();
        $buySent = new BuySent();

        $loop = true;
        while ($loop) {
            try {
                foreach ($buyReady->getHealthyRecords() as $row) {
                    $buyClientOrderId = 'repeater_'.$row->id.'_buy_'.\time();
                    $buyReady->setNextState($row->id, ['buy_client_order_id' => $buyClientOrderId]);

                    $order = new ForceMaker(
                        new Side('buy'),
                        new Symbol(Pair::getInstance($row->symbol)),
                        new Amount($row->buy_amount),
                        new Price($row->buy_price),
                        new ClientOrderId($buyClientOrderId)
                    );

                    $response = $order->getResponse();
                    $msg = \json_decode($response['body']);

                    if ($response['code'] === 200 && $msg->order_id) {
                        $buySent->setNextState(
                            $row->id,
                            ['buy_order_id' => $msg->order_id, 'buy_json' => $response['body']]
                        );
                        $output->writeln(\sprintf(
                            "%s\tBuy Order ID %s placed on %s pair for amount of %s at rate of %s",
                            (new \DateTime())->format('Y-m-d H:i:s'),
                            $msg->order_id,
                            $msg->symbol,
                            $msg->original_amount,
                            $msg->price
                        ));
                        if ($msg->price !== $row->buy_price) {
                            $output->writeln(\sprintf(
                                "%s\t\t<fg=yellow>(original buy price: %s)</>",
                                (new \DateTime())->format('Y-m-d H:i:s'),
                                $row->buy_price
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
