<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderPlacement;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelOrderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CancelOrder extends Command
{
    protected static $defaultName = 'order-placement:cancel';

    /**
     * @var CancelOrderInterface
     */
    private $cancel;

    public function __construct(
        CancelOrderInterface $cancelOrderInterface
    ) {
        $this->cancel = $cancelOrderInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Cancel an individual open order on the Gemini exchange.');
        $this->addArgument('order_id', InputArgument::REQUIRED, 'Order id on exchange.');
        $this->addOption('raw' , 'r', InputOption::VALUE_OPTIONAL, 'Output raw response data', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->cancel->cancel($input->getArgument('order_id'));
        if ($input->getOption('raw')) {
            $output->writeln(\json_encode($data));
        } else {
            $output->writeln("<options=bold,underscore>Order Cancelled:</>");
            $output->writeln("  Order Id:\t\t\t$data->order_id");
            if (\property_exists($data, 'client_order_id')) {
                $output->writeln("  Client Order Id:\t\t$data->client_order_id");
            }
            $output->writeln("  Symbol:\t\t\t$data->symbol");
            $output->writeln("  Side:\t\t\t\t$data->side");
            $output->writeln("  Original Amount:\t\t$data->original_amount");
            $output->writeln("  Price:\t\t\t$data->price");
            $output->writeln("  Executed Amount:\t\t$data->executed_amount");
            if ($data->executed_amount !== "0") {
                $output->writeln("  Average Execution Price:\t$data->avg_execution_price");
            }
        }
    }
}
