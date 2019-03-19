<?php

namespace Kobens\Gemini\Command\Command\Order\Placement;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder as PlaceNewOrder;
use Kobens\Gemini\Command\Argument\{
    Amount, ClientOrderId, Price, Side, Symbol
};
use Kobens\Gemini\Command\Traits\{
    Traits, GetAmount, GetClientOrderId, GetPrice, GetSide, GetSymbol
};
use Kobens\Gemini\Exception\Api\InsufficientFundsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Exchange\Exception\Order\MakerOrCancelWouldTakeException;

class NewOrder extends Command
{
    use Traits;
    use GetAmount, GetClientOrderId, GetPrice, GetSide, GetSymbol;

    protected function configure()
    {
        $this->setName('order:place:new');
        $this->setDescription('Places a new order on the exchange.');
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
        $order = new PlaceNewOrder(
            $this->getSide($input),
            $this->getSymbol($input),
            $this->getAmount($input),
            $this->getPrice($input),
            $this->getClientOrderId($input)
        );

        try {
            $output->writeln($order->getResponse());
        } catch (InsufficientFundsException $e) {
            $output->writeln($e->getMessage());
        } catch (MakerOrCancelWouldTakeException $e) {
            $output->writeln('<fg=white;bg=red>Unable to place order. Maker or cancel rule applied.</>');
        } catch (\Exception $e) {
            $output->writeln([
                \sprintf('<fg=white;bg=red>Error Code: %s</>', $e->getCode()),
                \sprintf('<fg=white;bg=red>Message: %s</>', $e->getMessage()),
            ]);
        }
    }

}