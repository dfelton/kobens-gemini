<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\OrderStatus;

use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\GetActiveOrdersInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetActiveOrders extends Command
{
    protected static $defaultName = 'order-status:get-active';

    /**
     * @var HostInterface
     */
    private $host;

    /**
     * @var GetActiveOrdersInterface
     */
    private $activeOrders;

    public function __construct(
        HostInterface $hostInterface,
        GetActiveOrdersInterface $getActiveOrdersInterface
    ) {
        $this->host = $hostInterface;
        $this->activeOrders = $getActiveOrdersInterface;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('List all active orders.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $orders = $this->activeOrders->getOrders();

        if ($orders === []) {
            $output->writeln(\sprintf(
                '<fg=red>There are currently no active orders on "%s."</>',
                $this->host->getHost()
            ));
        } else {
            \Zend\Debug\Debug::dump($orders);
        }
    }

}
