<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Kobens\Gemini\Exchange\Currency\Pair;

final class AddToPosition extends Command
{
    protected static $defaultName = 'repeater:add-to';

    private AddAmount $addAmount;

    private TradeResource $tradeResource;

    public function __construct(
        AddAmount $addAmount,
        TradeResource $tradeResource
    ) {
        $this->addAmount = $addAmount;
        $this->tradeResource = $tradeResource;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('symbol', InputArgument::REQUIRED, 'ID of Repeater Record');
        $this->addArgument('amount', InputArgument::REQUIRED, 'Additional Amount to Buy');
        $this->addArgument('price-from', InputArgument::REQUIRED, 'Price from');
        $this->addArgument('price-to', InputArgument::REQUIRED, 'Price to');
        $this->addOption('confirm', 'c', InputOption::VALUE_OPTIONAL, 'Confirm', '0');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $pair = Pair::getInstance($input->getArgument('symbol'));
        $amount = $this->getArg($input, 'amount');
        $priceFrom = $this->getArg($input, 'price-from');
        $priceTo = $this->getArg($input, 'price-to');
        if ($input->getOption('confirm') === '1') {
            try {
                $this->addAmount->addTo($pair->getSymbol(), $amount, $priceFrom, $priceTo);
            } catch (\Throwable $e) {
                $exitCode = 1;
                $output->writeln(sprintf(
                    "Error Code: %d\nError Message: %s\nStack Trace:\n%s",
                    $e->getCode(),
                    $e->getMessage(),
                    $e->getTraceAsString()
                ));
            }
        }
        return $exitCode;
    }

    private function getArg(InputInterface $input, string $arg): string
    {
        $amount = (string) $input->getArgument($arg);
        foreach (strpos($amount, '.') === false ? [$amount] : explode('.', $amount) as $i => $part) {
            if (ctype_digit($part) !== true || $i > 1) {
                throw new \InvalidArgumentException(sprintf('"%s" is not a valid number.', $amount));
            }
        }
        return $amount;
    }
}
