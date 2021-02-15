<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Info;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Exchange\Currency\Pair as P;
use Symfony\Component\Console\Helper\Table;

final class Pair extends Command
{
    protected function configure(): void
    {
        $this->setName('info:pair');
        $this->setDescription('Outputs information about a particular trading pair');
        $this->addArgument('pair', InputArgument::REQUIRED, 'Pair to fetch info about');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        try {
            $this->getTable(
                $output,
                P::getInstance($input->getArgument('pair'))
            )->render();
        } catch (\Throwable $e) {
            $exitCode = 1;
            $output->writeln([
                'Error Message: ' . $e->getMessage(),
                'Error Code: ' . $e->getCode(),
                "Stack Trace: \n" . $e->getTraceAsString()
            ]);
        }
        return $exitCode;
    }

    private function getTable(OutputInterface $output, P $pair): Table
    {
        $base = strtoupper($pair->getBase()->getSymbol());
        $quote = strtoupper($pair->getQuote()->getSymbol());
        $table = new Table($output);
        $table->addRow([
            'Minimum Order Increment',
            sprintf(
                '%s %s%s',
                $pair->getMinOrderIncrement(),
                $base,
                $this->getENotation($pair->getMinOrderIncrement())
            ),
        ]);
        $table->addRow([
            'Minimum Order Size',
            sprintf(
                '%s %s%s',
                $pair->getMinOrderSize(),
                $base,
                $this->getENotation($pair->getMinOrderSize())
            ),
        ]);
        $table->addRow([
            'Minimum Price Increment',
            sprintf(
                '%s %s%s',
                $pair->getMinPriceIncrement(),
                $quote,
                $this->getENotation($pair->getMinPriceIncrement())
            )
        ]);
        return $table;
    }

    private function getENotation(string $num): string
    {
        if (strpos($num, '.') !== false) {
            $num = rtrim(rtrim($num, '0'), '.');
        }
        if (strpos($num, '.') !== false) {
            $parts = explode('.', $num);
            $e = 0 - strlen($parts[1]);
            $val = ' (' . ltrim(implode('', $parts), '0') . 'e' . $e . ')';
        } else {
            $val = '';
        }
        return $val;
    }
}
