<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Multiply;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class AddToPosition extends Command
{
    protected static $defaultName = 'repeater:add-to';

    private AddAmount $addAmount;

    private \Kobens\Gemini\TradeRepeater\Model\Resource\Trade $tradeResource;

    public function __construct(
        AddAmount $addAmount,
        \Kobens\Gemini\TradeRepeater\Model\Resource\Trade $tradeResource
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
            $addTo = $this->addAmount->addTo($pair->getSymbol(), $amount, $priceFrom, $priceTo);
            try {
                foreach ($addTo as $result) {
                    /** @var \Kobens\Gemini\TradeRepeater\Model\Trade $trade */
                    $trade = $result['trade'];
                    $amount = $result['amount'];
                    $newAmount = Add::getResult($amount, $trade->getBuyAmount());
                    $output->writeln(sprintf(
                        'Amount of %s added to %s record. Was buy %s %s @ %s %s/%s, now buy %s',
                        $amount,
                        $trade->getSymbol(),
                        $trade->getBuyAmount(),
                        strtoupper($pair->getBase()->getSymbol()),
                        $trade->getBuyPrice(),
                        strtoupper($pair->getBase()->getSymbol()),
                        strtoupper($pair->getQuote()->getSymbol()),
                        $newAmount
                    ));
                }
            } catch (\Throwable $e) {
                $exitCode = 1;
                $output->writeln(sprintf(
                    "Error Code: %d\nError Message: %s\nStack Trace:\n%s",
                    $e->getCode(),
                    $e->getMessage(),
                    $e->getTraceAsString()
                ));
            }
        } else {
            $data = $this->getSummary($pair->getSymbol(), $amount, $priceFrom, $priceTo);
            $table = new Table($output);
            foreach ($data as $label => $value) {
                $table->addRow([
                    ucwords(str_replace('_', ' ', $label)),
                    $value
                ]);
            }
            $table->render();
        }
        return $exitCode;
    }

    private function getSummary(string $symbol, string $amount, string $priceFrom, string $priceTo): array
    {
        /** @var \Kobens\Gemini\TradeRepeater\Model\Trade $trade */
        $filters = ['buy_price_gte' => $priceFrom, 'buy_price_lte' => $priceTo, 'status' => 'BUY_PLACED'];
        $data = [
            'total_records' => 0,
            'total_quote' => '0',
            'total_base' => '0',
        ];
        $lowestBuyPrice = null;
        $highestBuyPrice = null;
        foreach ($this->tradeResource->getList($symbol, $filters) as $trade) {
            $costBasis = Multiply::getResult($amount, $trade->getBuyPrice());
            $deposit = Multiply::getResult($costBasis, '0.0035'); // TODO: Reference constant
            $data['total_quote'] = Add::getResult(
                $data['total_quote'],
                Add::getResult($costBasis, $deposit)
            );
            $data['total_base'] = Add::getResult($data['total_base'], $amount);
            ++$data['total_records'];
            if ($lowestBuyPrice === null || Compare::getResult($lowestBuyPrice, $trade->getBuyPrice()) === Compare::LEFT_GREATER_THAN) {
                $lowestBuyPrice = $trade->getBuyPrice();
            }
            if ($highestBuyPrice === null || Compare::getResult($highestBuyPrice, $trade->getBuyPrice()) === Compare::LEFT_LESS_THAN) {
                $highestBuyPrice = $trade->getBuyPrice();
            }
        }
        $data['lowest_buy_price'] = $lowestBuyPrice;
        $data['highest_buy_price'] = $highestBuyPrice;
        return $data;
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
