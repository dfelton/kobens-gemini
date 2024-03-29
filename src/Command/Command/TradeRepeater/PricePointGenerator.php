<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Exchange\PairInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\PricePoint;
use Kobens\Gemini\TradeRepeater\PricePointGenerator\Result;
use Kobens\Math\BasicCalculator\Add;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\TableGateway\TableGatewayInterface;
use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Bucket;
use Kobens\Math\BasicCalculator\Compare;
use Zend\Db\Adapter\Adapter;

final class PricePointGenerator extends Command
{
    protected static $defaultName = 'repeater:ppg';

    /**
     * @var TableGatewayInterface
     */
    private TableGatewayInterface $table;

    private \Kobens\Gemini\TradeRepeater\PricePointGenerator $generator;

    private Bucket $bucket;

    private Adapter $adapter;

    public function __construct(
        Adapter $adapter,
        Bucket $bucket,
        TableGatewayInterface $table,
        \Kobens\Gemini\TradeRepeater\PricePointGenerator $generator
    ) {
        $this->adapter = $adapter;
        $this->bucket = $bucket;
        $this->table = $table;
        $this->generator = $generator;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Price Point Generator.');
        $this->addArgument('symbol', InputArgument::REQUIRED, 'Trading Pair Symbol');
        $this->addArgument('buy_amount', InputArgument::REQUIRED, 'Buy Amount per Order');
        $this->addArgument('buy_price_start', InputArgument::REQUIRED, 'Buy Price Start');
        $this->addArgument('buy_price_end', InputArgument::REQUIRED, 'Buy Price End');
        $this->addArgument('increment', InputArgument::REQUIRED, 'Increment amount between buy orders');
        $this->addArgument('sell_after_gain', InputArgument::REQUIRED, 'Sell After Gain (1 = same price as purchase, 2 = 100% gain from purchase price)');
        $this->addArgument('save_amount', InputArgument::OPTIONAL, 'Save Amount', 0);
        $this->addArgument('is_enabled', InputArgument::OPTIONAL, 'Is Enabled', 1);
        $this->addOption('bucket', 'b', InputOption::VALUE_OPTIONAL, 'Use funds from bucket to create records.', '1');
        $this->addOption('create', 'c', InputOption::VALUE_OPTIONAL, 'Create records (if omitted, will simply report summary)', 0);
        $this->addOption('increment-by-percent', 'i', InputOption::VALUE_OPTIONAL, 'Interpret provided increment value as a percentage', '1');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        try {
            $this->main($input, $output);
        } catch (\Throwable $e) {
            $output->writeln([
                "<fg=red>Error Message: {$e->getMessage()}</>",
                "<fg=red>Error Code: {$e->getCode()}</>",
            ]);
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln("Trace:\n{$e->getTraceAsString()}");
            }
            $exitCode = 1;
        }
        return $exitCode;
    }

    private function main(InputInterface $input, OutputInterface $output): void
    {
        $pair = Pair::getInstance($input->getArgument('symbol'));
        $create = $input->getOption('create') === '1';
        $result = $this->generator->get(
            $pair,
            (string) $input->getArgument('buy_amount'),
            (string) $input->getArgument('buy_price_start'),
            (string) $input->getArgument('buy_price_end'),
            (string) $input->getArgument('increment'),
            (string) $input->getArgument('sell_after_gain'),
            (string) $input->getArgument('save_amount'),
            $create === false,
            $input->getOption('increment-by-percent') === '1'
        );
        $isEnabled = (int) $input->getArgument('is_enabled');
        if ($create) {
            $total = 0;
            /** @var PricePoint $item */
            foreach ($this->create($output, $pair, $result, $isEnabled, $input->getOption('bucket') === '1') as $item) {
                ++$total;
                $output->writeln(sprintf(
                    "Inserting %s record for %s buy amount at %s price.\tSell %s at %s price.",
                    $pair->getSymbol(),
                    $item->getBuyAmountBase(),
                    $item->getBuyPrice(),
                    $item->getSellAmountBase(),
                    $item->getSellPrice()
                ));
            }
            $output->writeln(sprintf(
                'Total of %d records inserted for the %s pair.',
                $total,
                $pair->getSymbol()
            ));
        } else {
            $this->summarize($input, $output, $pair, $result);
        }
    }

    private function summarize(InputInterface $input, OutputInterface $output, Pair $pair, Result $result): void
    {
        $base = $pair->getBase();
        $quote = $pair->getQuote();
        $pricePoints = $result->getPricePoints();

        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            foreach ($pricePoints as $i => $pricePoint) {
                $this->reportPricePoint(
                    $output,
                    new TableCell(sprintf("Order %d", $i), ['colspan' => 2]),
                    $pricePoint,
                    $pair
                );
            }
        } else {
            $this->reportPricePoint(
                $output,
                new TableCell('First Order', ['colspan' => 2]),
                reset($pricePoints),
                $pair
            );
            $this->reportPricePoint(
                $output,
                new TableCell('Last Order', ['colspan' => 2]),
                end($pricePoints),
                $pair
            );
        }

        $table = new Table($output);
        $table->setHeaders([new TableCell('Price Point Summary', ['colspan' => 2])]);
        $table->addRow(['Buy Amount', (string) $input->getArgument('buy_amount')]);
        $table->addRow(['Buy Price Start', (string) $input->getArgument('buy_price_start')]);
        $table->addRow(['Buy Price End', (string) $input->getArgument('buy_price_end')]);
        $table->addRow(['Sell After Gain', (string) $input->getArgument('sell_after_gain')]);
        $table->addRow(['Save Amount', (string) $input->getArgument('save_amount')]);
        $table->addRow(['Increment Config', (string) $input->getArgument('increment')]);
        $table->addRow(['Order Count', \count($pricePoints)]);
        $table->addRow([
            \sprintf('%s Buy Amount', strtoupper($base->getSymbol())),
            $result->getTotalBuyBase(),
        ]);
        $table->addRow([
            sprintf('%s Amount w/ Fees', strtoupper($quote->getSymbol())),
            Add::getResult($result->getTotalBuyFeesHold(), $result->getTotalBuyQuote()),
        ]);
        $table->addRow([
            sprintf('%s Sell Amount', strtoupper($base->getSymbol())),
            $result->getTotalSellBase(),
        ]);
        $table->addRow([
            sprintf('%s Total Profits', strtoupper($base->getSymbol())),
            $result->getTotalProfitBase(),
        ]);
        $table->addRow([
            sprintf('%s Total Profits', strtoupper($quote->getSymbol())),
            $result->getTotalProfitQuote(),
        ]);
        $table->render();
    }

    private function reportPricePoint(OutputInterface $output, TableCell $header, PricePoint $pricePoint, PairInterface $pair): void
    {
        $table = new Table($output);
        $table->setHeaders([$header]);
        $table->addRow([
            'Price (Buy)',
            $pricePoint->getBuyPrice()
        ]);
        $table->addRow([
            'Price (Sell)',
            $pricePoint->getSellPrice()
        ]);
        $table->addRow([
            'Amount (Buy)',
            $pricePoint->getBuyAmountBase()
        ]);
        $table->addRow([
            sprintf('Required %s for Buy', $pair->getQuote()->getSymbol()),
            Add::getResult($pricePoint->getBuyAmountQuote(), $pricePoint->getBuyFeeHold())
        ]);
        $table->addRow([
            'Amount (Sell)',
            $pricePoint->getSellAmountBase()
        ]);
        $table->addRow([
            sprintf('Profit %s', strtoupper($pair->getBase()->getSymbol())),
            $pricePoint->getProfitBase()
        ]);
        $table->addRow([
            sprintf('Profit %s', strtoupper($pair->getQuote()->getSymbol())),
            $pricePoint->getProfitQuote()
        ]);
        $table->render();
    }

    /**
     * @param OutputInterface $output
     * @param PricePoint[] $pricePoints
     */
    private function create(OutputInterface $output, Pair $pair, Result $result, bool $isEnabled, bool $pullFromBucket): \Generator
    {
        if ($pullFromBucket && !$this->hasFunds($result, $pair)) {
            throw new \InvalidArgumentException(sprintf(
                'Not enough funds in "%s" bucket to create positions.',
                $pair->getQuote()->getSymbol()
            ));
        }
        foreach ($result->getPricePoints() as $position) {
            $this->adapter->driver->getConnection()->beginTransaction();
            try {
                if ($pullFromBucket) {
                    $this->bucket->removeFromBucket($pair->getQuote()->getSymbol(), Add::getResult($position->getBuyAmountQuote(), $position->getBuyFeeHold()));
                }
                $this->table->insert([
                    'is_enabled' => $isEnabled,
                    'status' => 'BUY_READY',
                    'symbol' => $pair->getSymbol(),
                    'buy_amount' => $position->getBuyAmountBase(),
                    'buy_price' => $position->getBuyPrice(),
                    'sell_amount' => $position->getSellAmountBase(),
                    'sell_price' => $position->getSellPrice(),
                ]);
                $this->adapter->driver->getConnection()->commit();
                yield $position;
            } catch (\Throwable $e) {
                $this->adapter->driver->getConnection()->rollback();
                throw $e;
            }
        }
    }

    private function hasFunds(Result $result, Pair $pair): bool
    {
        $contains = $this->bucket->get($pair->getQuote()->getSymbol());
        $required = Add::getResult(
            $result->getTotalBuyQuote(),
            $result->getTotalBuyFeesHold(),
        );
        $compare = Compare::getResult($contains, $required);
        return $compare === Compare::LEFT_GREATER_THAN || $compare === Compare::EQUAL;
    }
}
