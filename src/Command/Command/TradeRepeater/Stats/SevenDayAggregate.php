<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\Stats;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\TradeRepeater\Stats\SevenDayAggregate\Updater;

final class SevenDayAggregate extends Command
{
    private Updater $updater;

    public function __construct(
        Updater $updater
    ) {
        $this->updater = $updater;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('cron:repeater:stats:7d');
        $this->setDescription('Seven Day Aggregate Stats Table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->updater->execute();
        return 0;
    }
}
