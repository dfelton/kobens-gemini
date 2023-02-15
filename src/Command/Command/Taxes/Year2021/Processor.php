<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Taxes\Year2021;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Processor extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'taxes:processor:2021';

    protected function configure(): void
    {
        $this->setDescription(
            'Processes 2021 taxable events.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
    }
}
