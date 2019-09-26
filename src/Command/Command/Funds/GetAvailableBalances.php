<?php

namespace Kobens\Gemini\Command\Command\Funds;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Api\Rest\Request\Funds\Balances;

final class GetAvailableBalances extends Command
{
    protected static $defaultName = 'funds:balances';

    protected function configure()
    {
        $this->setDescription('Returns available balances.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $balances = (new Balances())->getResponse()['body'];
            $balances = \json_decode($balances, true);
            \Zend\Debug\Debug::dump($balances);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            \Zend\Debug\Debug::dump($e->getTraceAsString());
        }
    }
}