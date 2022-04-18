<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Taxes\Pre2022;

use Kobens\Core\Db;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

final class Form8949 extends Command
{
    protected static $defaultName = 'taxes:pre-2022:form8949';

    /**
     * @var int
     */
    private int $year;

    /**
     * @var TableGatewayInterface[]
     */
    private array $tables = [];

    private const BOILER_PLATE_SHORT_LONG = [
        'count'         => 0,
        'capital_gain'  => '0',
        'cost_basis'    => '0',
        'date_sale'     => null,
        'proceeds'      => '0',
        'total_asset_sold' => '0'
    ];

    private const BOILER_PLATE_SYMBOL = [
        'totalProceeds' => '0',
        'totalCostBasis' => '0',
        'long' => self::BOILER_PLATE_SHORT_LONG,
        'short' => self::BOILER_PLATE_SHORT_LONG,
    ];

    protected function configure(): void
    {
        $this->addArgument('year', InputArgument::OPTIONAL, 'Year to run report on. Defaults to prior year', ((int) date('Y')) - 1);
        $this->setDescription('US Federal Tax Form 8949');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->year = (int) $input->getArgument('year');
        $output->writeln($this->getForm8949($this->getData()));
        return 0;
    }

    /**
     * @return array
     */
    private function getData(): array
    {
        $data = [];
        foreach ($this->getResultSets() as $arr) {
            $symbol = strtoupper(substr($arr[0], 0, -3));
            foreach ($arr[1] as $row) {
                if (($data[$symbol] ?? null) === null) {
                    $data[$symbol] = self::BOILER_PLATE_SYMBOL;
                }
                $this->recordTransaction($data[$symbol], $row);
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @param \ArrayObject $transaction
     */
    private function recordTransaction(array &$data, \ArrayObject $transaction): void
    {
        $data['totalProceeds'] = Add::getResult($data['totalProceeds'], $transaction['proceeds']);
        $data['totalCostBasis'] = Add::getResult($data['totalCostBasis'], $transaction['cost_basis']);

        $term = $this->getTerm($transaction['buy_date'], $transaction['sell_date']);
        ++$data[$term]['count'];
        $data[$term]['capital_gain'] = Add::getResult($data[$term]['capital_gain'], $transaction['capital_gain']);
        $data[$term]['cost_basis'] = Add::getResult($data[$term]['cost_basis'], $transaction['cost_basis']);
        $data[$term]['proceeds'] = Add::getResult($data[$term]['proceeds'], $transaction['proceeds']);
        $data[$term]['total_asset_sold'] = Add::getResult($data[$term]['total_asset_sold'], $transaction['amount']);

        if ($data[$term]['date_sale'] === null || \strtotime($data[$term]['date_sale']) < \strtotime($transaction['sell_date'])) {
            $data[$term]['date_sale'] = $transaction['sell_date'];
        }
    }

    private function getForm8949(array $data): array
    {
        $totalProceeds = '0';
        $totalCostBasis = '0';
        $totalGainLoss = '0';
        $totalTrades = 0;

        $output = [\sprintf('<options=bold>Form 8949 - %d</>', $this->year)];

        foreach (['short', 'long'] as $term) {
            $output[] = "\n<options=bold>" . ucfirst($term) . " Term Sales:</>";
            $output[] = ' (a) Description of Property |' .
                ' (b) Date Acquired |' .
                ' (c) Date Sold | ' .
                ' (d) Proceeds | ' .
                ' (e) Cost Basis | ' .
                ' (h) Gain or Loss ';
            foreach ($data as $symbol => $symbolData) {
                $this->getForm8949ByTerm(
                    $term,
                    $output,
                    $symbol,
                    $symbolData,
                    $totalProceeds,
                    $totalCostBasis,
                    $totalGainLoss,
                    $totalTrades
                );
            }

        }
        $output[] = "\nGrand Total Summary:\n";
        $output[] = \sprintf(
            "\tGross Proceeds:    $%s",
            str_pad(number_format((float) $totalProceeds, 2), 12, ' ', STR_PAD_LEFT)
        );
        $output[] = \sprintf(
            "\tCost Basis:        $%s",
            str_pad(number_format((float) $totalCostBasis, 2), 12, ' ', STR_PAD_LEFT)
        );
        $output[] = \sprintf(
            "\tCapital Gain/Loss: $%s",
            str_pad(number_format((float) $totalGainLoss, 2), 12, ' ', STR_PAD_LEFT)
        );
        $output[] = \sprintf("\tTotal Transactions:        %s", $totalTrades);
        return $output;
    }

    private function getForm8949ByTerm(
        string $term,
        array &$output,
        string $symbol,
        array $data,
        string &$totalProceeds,
        string &$totalCostBasis,
        string &$totalGainLoss,
        int &$totalTrades
    ): void {
        if ($data[$term]['count'] !== 0) {
            $totalTrades += $data[$term]['count'];
            $date = explode('-', explode(' ', $data[$term]['date_sale'])[0]);

            $output[] = \sprintf(
                "%s |           Various |    %s | %s |  %s |  %s",
                str_pad(
                    $data[$term]['total_asset_sold'] . ' ' . $symbol,
                    28,
                    ' ',
                    STR_PAD_LEFT
                ),
                $date[1] . '/' . $date[2] . '/' . $date[0],
                str_pad('$' . number_format((float) $data[$term]['proceeds'], 2), 13, ' ', STR_PAD_LEFT),
                str_pad('$' . number_format((float) $data[$term]['cost_basis'], 2), 14, ' ', STR_PAD_LEFT),
                str_pad('$' . number_format((float) $data[$term]['capital_gain'], 2), 16, ' ', STR_PAD_LEFT),
            );

            $totalProceeds = Add::getResult(
                $totalProceeds,
                number_format((float) $data[$term]['proceeds'], 2, '.', '')
            );
            $totalCostBasis  = Add::getResult(
                $totalCostBasis,
                number_format((float) $data[$term]['cost_basis'], 2, '.', '')
            );
            $totalGainLoss  = Add::getResult(
                $totalGainLoss,
                number_format((float) $data[$term]['capital_gain'], 2, '.', '')
            );
        }
    }

    /**
     * Return if it is a long or short term trade
     *
     * @param string $buyDate
     * @param string $sellDate
     * @return string               'long' or 'short'
     */
    private function getTerm(string $buyDate, string $sellDate): string
    {
        $hodlDateStart = \strtotime('+1 days', \strtotime(\substr($buyDate, 0, 10)));
        $hodlDateStart = \strtotime('+1 years', $hodlDateStart);
        $sellDateStart = \strtotime(\substr($sellDate, 0, 10));
        return $hodlDateStart <= $sellDateStart ? 'long' : 'short';
    }

    /**
     * Yields [string, \Zend\Db\ResultSet\ResultSetInterface]
     *
     * @param InputInterface $input
     * @return \Generator
     */
    private function getResultSets(): \Generator
    {
        $tables = $this->getTables();
        foreach ($tables as $symbol => $table) {
            yield [
                $symbol,
                $table->select(function (Select $select) use ($symbol) {
                    $select->join(
                        ['buyHistory' => "trade_history_{$symbol}"],
                        "buyHistory.tid = taxes_{$symbol}_sell_log.buy_tid",
                        [
                            'buy_date' => 'trade_date',
                            'buy_price' => 'price'
                        ]
                    );
                    $select->join(
                        ['saleHistory' => "trade_history_{$symbol}"],
                        "saleHistory.tid = taxes_{$symbol}_sell_log.sell_tid",
                        [
                            'sell_date' => 'trade_date',
                            'sell_price' => 'price',
                        ]
                    );
                    $select->where->greaterThanOrEqualTo('saleHistory.trade_date', $this->year . '-01-01 00:00:00.000');
                    $select->where->lessThan('saleHistory.trade_date', ($this->year + 1) . '-01-01 00:00:00.000');
                    $select->order([
                        "taxes_{$symbol}_sell_log.sell_tid ASC",
                        "taxes_{$symbol}_sell_log.buy_tid ASC"
                    ]);
                })
            ];
        }
    }

    /**
     * @return TableGateway[]
     */
    private function getTables(): array
    {
        if ($this->tables === []) {
            foreach (array_keys(Pair::getAllInstances()) as $symbol) {
                if (substr($symbol, -3, 3) === 'usd') {
                    $this->tables[$symbol] = new TableGateway("taxes_{$symbol}_sell_log", Db::getAdapter());
                }
            }
        }
        return $this->tables;
    }
}
