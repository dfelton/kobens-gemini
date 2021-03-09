<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater\CliMonitor;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\TradeRepeater\CliMonitor\AverageOrderSize as ModelAverageOrderSize;
use Symfony\Component\Console\Input\InputOption;

final class AverageOrderSize extends Command
{
    protected static $defaultName = 'repeater:monitor:avg-size';

    private ModelAverageOrderSize $averageOrderSize;

    public function __construct(
        ModelAverageOrderSize $averageOrderSize
    ) {
        $this->averageOrderSize = $averageOrderSize;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('base', 'b', InputOption::VALUE_OPTIONAL, 'Base Currency');
        $this->addOption('quote', 'Q', InputOption::VALUE_OPTIONAL, 'Quote Currency');

        $this->addOption('count-base', null, InputOption::VALUE_OPTIONAL, 'Count Average base amount instead of quote amount', '0');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $base = $input->getOption('base');
        $quote = $input->getOption('quote');
        if ($base === null && $quote === null) {
            $output->writeln('<fg=red>Please provide either a base or quote currency</>');
            $exitCode = 1;
        } else {
            try {
                if ($input->getOption('count-base') === '1') {
                    $avg = $this->averageOrderSize->getAverageBaseAmount($base);
                    $output->writeln(sprintf(
                        'Average Order Amount: %s %s across %d orders',
                        $avg['avg'],
                        $base,
                        $avg['count']
                    ));
                } else {
                    $avg = $this->averageOrderSize->getAverageQuoteAmount($quote, $base);
                    $output->writeln(sprintf(
                        'Average Order Amount: %s %s across %d orders',
                        $avg['avg'],
                        $quote,
                        $avg['count']
                    ));
                    $output->writeln(sprintf(
                        'Average Amount of %s necessary for minimum order increment: %s %s across %d orders',
                        $quote,
                        $avg['min'],
                        $quote,
                        $avg['count']
                    ));
                }
            } catch (\Throwable $e) {
                $exitCode = 1;
                $output->write($e->getMessage());
                $output->write($e->getTraceAsString());
            }
        }
        return $exitCode;
    }
}
