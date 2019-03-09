<?php

namespace Kobens\Gemini\App\Command\Order;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\App\Command\Argument\Symbol;
use Kobens\Gemini\App\Command\Argument\Side;
use Kobens\Gemini\App\Command\Traits\CommandTraits;

class NewOrder extends Command
{
    use CommandTraits;

    protected static $defaultName = 'order:new';

    protected function configure()
    {
        $this->setDescription('Places a new order on the exchange.');
        $this->addArgList([new Side(), new Symbol()], $this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('woot woot');
    }
}