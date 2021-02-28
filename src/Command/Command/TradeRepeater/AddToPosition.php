<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Gemini\Command\Traits\GetIntArg;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Bucket;
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
    use GetIntArg;

    protected static $defaultName = 'repeater:add-to';

    private AddAmount $addAmount;

    private \Kobens\Gemini\TradeRepeater\Model\Resource\Trade $tradeResource;

    private Bucket $bucket;

    public function __construct(
        AddAmount $addAmount,
        Bucket $bucket,
        \Kobens\Gemini\TradeRepeater\Model\Resource\Trade $tradeResource
    ) {
        $this->addAmount = $addAmount;
        $this->bucket = $bucket;
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
        $this->addOption('bucket', 'b', InputOption::VALUE_OPTIONAL, 'Use funds from bucket for action.', '1');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $pair = Pair::getInstance($input->getArgument('symbol'));
        $amount = $this->getArg($input, 'amount');
        $priceFrom = $this->getArg($input, 'price-from');
        $priceTo = $this->getArg($input, 'price-to');
        if ($input->getOption('confirm') === '1') {
            if ($this->getIntArg($input, 'bucket', 0) === 1) {
                $this->pullFromBucket($pair, $amount, $priceFrom, $priceTo);
            }

            $addTo = $this->addAmount->addTo($pair->getSymbol(), $amount, $priceFrom, $priceTo);
            try {
                foreach ($addTo as $result) {
                    /** @var Trade $trade */
                    $trade = $result['trade'];
                    $amountAdded = $result['amount_added'];
                    if (Compare::getResult($amountAdded, '0') === Compare::LEFT_GREATER_THAN) {
                        $newAmount = Add::getResult($amountAdded, $trade->getBuyAmount());
                        $output->writeln(sprintf(
                            'Amount of %s added to %s record. Was buy %s %s @ %s %s/%s, now buy %s',
                            $amountAdded,
                            $trade->getSymbol(),
                            $trade->getBuyAmount(),
                            strtoupper($pair->getBase()->getSymbol()),
                            $trade->getBuyPrice(),
                            strtoupper($pair->getBase()->getSymbol()),
                            strtoupper($pair->getQuote()->getSymbol()),
                            $newAmount
                        ));
                    } else {
                        $this->returnToBucket($pair, $trade, $amount);

                        $output->writeln(sprintf(
                            '<fg=yellow>%s record %d of buy %s %s @ %s %s/%s skipped. No amount added.</>',
                            $trade->getSymbol(),
                            $trade->getId(),
                            $trade->getBuyAmount(),
                            strtoupper($pair->getBase()->getSymbol()),
                            $trade->getBuyPrice(),
                            strtoupper($pair->getBase()->getSymbol()),
                            strtoupper($pair->getQuote()->getSymbol()),
                        ));
                    }
                }
            } catch (\Throwable $e) {
                $exitCode = 1;
                $output->writeln(sprintf(
                    "Error Message: %s\nError Code: %d\nStack Trace:\n%s",
                    $e->getMessage(),
                    $e->getCode(),
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

    private function returnToBucket(Pair $pair, Trade $trade, string $baseAmount): void
    {
        $costBasis = Multiply::getResult($trade->getBuyPrice(), $baseAmount);
        $deposit = Multiply::getResult($costBasis, '0.0035'); // TODO: Reference constant
        $total = Add::getResult($costBasis, $deposit);
        $this->bucket->addToBucket($pair->getQuote()->getSymbol(), $total);
    }

    private function pullFromBucket(Pair $pair, string $amount, string $priceFrom, string $priceTo): void
    {
        $amountToPull = $this->getSummary($pair->getSymbol(), $amount, $priceFrom, $priceTo)['total_quote'];
        $this->bucket->removeFromBucket($pair->getQuote()->getSymbol(), $amountToPull);
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
