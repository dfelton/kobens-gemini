<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Info;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\Exchange\Currency\Pair as P;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;

/**
 * Meant to output similar table as found here: https://docs.gemini.com/rest-api/#symbols-and-minimums
 */
final class Pair extends Command
{
    protected function configure(): void
    {
        $this->setName('info:pair');
        $this->setDescription('Outputs information about a particular trading pair');
        $this->addArgument('pair', InputArgument::OPTIONAL, 'Pair to fetch info about. Omit to output information on all pairs.');
        $this->addOption('quote', null, InputOption::VALUE_OPTIONAL, 'Fetch pairs with given quote currency.');
        $this->addOption('base', null, InputOption::VALUE_OPTIONAL, 'Fetch pairs with given base currency.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        try {
            $pairs = $this->getPairs($input);
            $table = new Table($output);
            $table->setHeaders([
                'Symbol',
                'Minimum Order Size',
                'Tick Size',
                'Quote Currency Price Increment',
            ]);
            foreach ($this->getData($pairs) as $row) {
                $table->addRow($row);
            }
            $table->render();
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

    /**
     * @param InputInterface $input
     * @return P[]
     */
    private function getPairs(InputInterface $input): array
    {
        $pairs = is_string($input->getArgument('pair'))
            ? [P::getInstance($input->getArgument('pair'))]
            : P::getAllInstances();
        if (is_string($input->getOption('quote'))) {
            $quote = strtolower($input->getOption('quote'));
            foreach ($pairs as $i => $pair) {
                if ($pair->getQuote()->getSymbol() !== $quote) {
                    unset($pairs[$i]);
                }
            }
        }
        if (is_string($input->getOption('base'))) {
            $base = strtolower($input->getOption('base'));
            foreach ($pairs as $i => $pair) {
                if ($pair->getBase()->getSymbol() !== $base) {
                    unset($pairs[$i]);
                }
            }
        }
        return array_values($pairs);
    }

    /**
     * @param P[] $pairs
     * @return array
     */
    private function getData(array $pairs): \Generator
    {
        foreach ($pairs as $pair) {
            $base = strtoupper($pair->getBase()->getSymbol());
            $quote = strtoupper($pair->getQuote()->getSymbol());
            yield [
                strtoupper($pair->getSymbol()),
                sprintf(
                    '%s %s%s',
                    $pair->getMinOrderSize(),
                    $base,
                    $this->getENotation($pair->getMinOrderSize())
                ),
                sprintf(
                    '%s %s%s',
                    $pair->getMinOrderIncrement(),
                    $base,
                    $this->getENotation($pair->getMinOrderIncrement())
                ),
                sprintf(
                    '%s %s%s',
                    $pair->getMinPriceIncrement(),
                    $quote,
                    $this->getENotation($pair->getMinPriceIncrement())
                )
            ];
        }
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
