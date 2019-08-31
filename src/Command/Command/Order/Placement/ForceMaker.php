<?php

namespace Kobens\Gemini\Command\Command\Order\Placement;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder\ForceMaker as PlaceNewMakerOrder;
use Kobens\Gemini\Command\Argument\Amount;
use Kobens\Gemini\Command\Argument\ClientOrderId;
use Kobens\Gemini\Command\Argument\Price;
use Kobens\Gemini\Command\Argument\Side;
use Kobens\Gemini\Command\Argument\Symbol;
use Kobens\Gemini\Command\Traits\GetAmount;
use Kobens\Gemini\Command\Traits\GetClientOrderId;
use Kobens\Gemini\Command\Traits\GetPrice;
use Kobens\Gemini\Command\Traits\GetSide;
use Kobens\Gemini\Command\Traits\GetSymbol;
use Kobens\Gemini\Command\Traits\Traits;
use Kobens\Gemini\Exception\Api\InsufficientFundsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ForceMaker extends Command
{
    use Traits;
    use GetAmount, GetClientOrderId, GetPrice, GetSide, GetSymbol;

    protected static $defaultName = 'order:force-maker';

    protected function configure()
    {
        $this->setDescription('Places a new maker order on the exchange. If price specified would result in a taker order, price will be adjusted for maker status.');
        $this->addArgList(
            [
                new Side(),
                new Symbol(),
                new Amount(),
                new Price(),
                new ClientOrderId(),
            ],
            $this
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $order = new PlaceNewMakerOrder(
            $this->getSide($input),
            $this->getSymbol($input),
            $this->getAmount($input),
            $this->getPrice($input),
            $this->getClientOrderId($input)
        );

        try {
            $response = $order->getResponse();
            $d = \json_decode($response['body'], true);

            $output->writeln("Order ID............ {$d['order_id']}");
            if (\array_key_exists('client_order_id', $d)) {
                $output->writeln("Client Order Id..... {$d['client_order_id']}");
            }
            $output->writeln("Symbol.............. {$d['symbol']}");
            $output->writeln("Side................ {$d['side']}");
            $output->writeln("Timestamp........... {$d['timestamp']}");
            $output->writeln("Timestamp MS........ {$d['timestampms']}");
            $output->writeln("Is Live............. <fg=".($d['is_live']?'green':'red').">".($d['is_live']?'true':'false')."</>");
            $output->writeln("Is Cancelled........ <fg=".($d['is_cancelled']?'green':'red').">".($d['is_cancelled']?'true':'false')."</>");
            $output->writeln("Is Hidden........... <fg=".($d['is_hidden']?'green':'red').">".($d['is_hidden']?'true':'false')."</>");
            $output->writeln("Was Forced.......... <fg=".($d['was_forced']?'green':'red').">".($d['was_forced']?'true':'false')."</>");
            $output->writeln("Remaining Amount.... {$d['remaining_amount']}");
            $output->writeln("Price............... {$d['price']}");
            $output->writeln("Original Amount..... {$d['original_amount']}");

        } catch (InsufficientFundsException $e) {
            $output->writeln($e->getMessage());
        } catch (\Exception $e) {
            $output->writeln([
                \sprintf('<fg=white;bg=red>Error Code: %s</>', $e->getCode()),
                \sprintf('<fg=white;bg=red>Message: %s</>', $e->getMessage()),
            ]);
        }
    }

}