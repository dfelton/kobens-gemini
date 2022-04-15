<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Taxes\Pre2022;

use Kobens\Core\Config;
use Kobens\Core\Db;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Math\BasicCalculator\Add;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Subtract;
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
        'date_purchase' => null,
        'date_sale'     => null,
        'proceeds'      => '0',
    ];

    private const BOILER_PLATE_SYMBOL = [
        'totalAssetsSold' => '0',
        'totalProceeds' => '0',
        'long' => self::BOILER_PLATE_SHORT_LONG,
        'short' => self::BOILER_PLATE_SHORT_LONG,
    ];

    protected function configure(): void
    {
        $this->addArgument('year', InputArgument::REQUIRED);
        $this->setDescription('US Federal Tax Form 8949 & Gemini 1099-K');
        $this->addArgument('compare_1099', InputArgument::OPTIONAL, 'Compare to 1099-K', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->year = (int) $input->getArgument('year');
        $data = $this->getData();
        $output->writeln($this->getForm8949($data['8949']));
        if ((int) $input->getArgument('compare_1099') === 1) {
            $output->write(PHP_EOL);
            $output->writeln($this->getForm1099K($data['1099-K']));
        }
        return 0;
    }

    /**
     * @return array
     */
    private function getData(): array
    {
        $data = [
            '8949' => [],
            '1099-K' => [
                'gross' => '0',
                'transactionIds' => [],
                'months' => [
                    '01' => '0',
                    '02' => '0',
                    '03' => '0',
                    '04' => '0',
                    '05' => '0',
                    '06' => '0',
                    '07' => '0',
                    '08' => '0',
                    '09' => '0',
                    '10' => '0',
                    '11' => '0',
                    '12' => '0',
                ],
            ]
        ];
        $i = 0;
        foreach ($this->getResultSets() as $arr) {
            $symbol = $arr[0];
            $resultSet = $arr[1];
            foreach ($resultSet as $row) {
                if (($data['8949'][$symbol] ?? null) === null) {
                    $data['8949'][$symbol] = self::BOILER_PLATE_SYMBOL;
                }
                $this->collectForm8949($data['8949'][$symbol], $row);
                $this->collectForm1099K($data['1099-K'], $row);
                $i++;
            }
        }
        return $data;
    }

    /**
     * @param OutputInterface $output
     * @param array $row
     * @param string $symbol
     * @param int $iteration
     */
    private function outputTransaction(OutputInterface $output, array $row, string $symbol, int $iteration): void
    {
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
            if ($iteration === 0 || $iteration % 50 === 0) {
                $this->outputHeaders($output);
            }
            $output->write([
                \str_pad(\sprintf('%s %s', $row['amount'], $symbol), 26, ' ', STR_PAD_RIGHT),
                \str_pad(\substr($row['buy_date'], 0, 10), 16, ' ', STR_PAD_RIGHT),
                \str_pad(\substr($row['sell_date'], 0, 10), 16, ' ', STR_PAD_RIGHT),
                \str_pad($row['proceeds'], 24, ' ', STR_PAD_RIGHT),
                \str_pad($row['cost_basis'], 24, ' ', STR_PAD_RIGHT),
                $row['capital_gain'] . "\n",
            ]);
        }
    }

    /***
     * @param array $data
     * @param \ArrayObject $transaction
     */
    private function collectForm1099K(array &$data, \ArrayObject $transaction): void
    {
        $data['transactionIds'][$transaction['sell_tid']] = true;
        $data['gross'] = Add::getResult($data['gross'], $transaction['proceeds']);
        $month = explode('-', explode(' ', $transaction['sell_date'])[0])[1];
        $data['months'][$month] = Add::getResult($data['months'][$month], $transaction['proceeds']);
    }

    /**
     * @param array $data
     * @return array
     */
    private function getForm1099K(array $data): array
    {
        $str = "<options=bold>%s</> %s\n\t%s";
        return [
            \sprintf('<options=bold,underscore>Form 1099-K %d</>', $this->year),
            \sprintf($str, '1a', 'Gross amount of transactions:', $this->format1099KGrossVal($data['gross'])),
            \sprintf($str, '3', 'Number of transactions:', (string) count($data['transactionIds'])),
            \sprintf($str, '5a', 'January', $this->format1099KMonthVal($data['months'], '01')),
            \sprintf($str, '5b', 'February', $this->format1099KMonthVal($data['months'], '02')),
            \sprintf($str, '5c', 'March', $this->format1099KMonthVal($data['months'], '03')),
            \sprintf($str, '5d', 'April', $this->format1099KMonthVal($data['months'], '04')),
            \sprintf($str, '5e', 'May', $this->format1099KMonthVal($data['months'], '05')),
            \sprintf($str, '5f', 'June', $this->format1099KMonthVal($data['months'], '06')),
            \sprintf($str, '5g', 'July', $this->format1099KMonthVal($data['months'], '07')),
            \sprintf($str, '5h', 'August', $this->format1099KMonthVal($data['months'], '08')),
            \sprintf($str, '5i', 'September', $this->format1099KMonthVal($data['months'], '09')),
            \sprintf($str, '5j', 'October', $this->format1099KMonthVal($data['months'], '10')),
            \sprintf($str, '5k', 'November', $this->format1099KMonthVal($data['months'], '11')),
            \sprintf($str, '5l', 'December', $this->format1099KMonthVal($data['months'], '12')),
        ];
    }

    private function format1099KGrossVal(string $gross): string
    {
        $gross = (string) round((float) $gross, 2);
        $total = '0';
        for ($i = 1; $i <= 12; $i++) {
            $total = Add::getResult(
                $total,
                $this->getExchangeReported1099KMonthValue($i)
            );
        }
        if ($total === $gross) {
            $color = 'green';
            $tip = 'data matches';
        } else {
            $color = 'red';
            if (Compare::getResult($gross, $total) === Compare::LEFT_GREATER_THAN) {
                $tip = sprintf(
                    'Exchange Reporting "%s". App data is "%s" more than exchange data',
                    $total,
                    Subtract::getResult($gross, $total)
                );
            } else {
                $tip = sprintf(
                    'Exchange Reporting "%s". App data is "%s" less than exchange data',
                    $total,
                    Subtract::getResult($total, $gross)
                );
            }
        }
        return sprintf("<fg=%s>%s</>\t(%s)", $color, $gross, $tip);
    }

    /**
     * @param array $months
     * @param string $month     '01' = 'jan', '02' = 'feb', etc
     * @return string
     */
    private function format1099KMonthVal(array $months, string $month): string
    {
        $exchangeValue = $this->getExchangeReported1099KMonthValue((int) $month);
        $rounded = (string) round((float) $months[$month], 2);
        $color = $rounded === $exchangeValue ? 'green' : 'red';
        if ($color === 'green') {
            $tip = 'data matches';
        } else {
            if (Compare::getResult($rounded, $exchangeValue) === Compare::LEFT_GREATER_THAN) {
                $tip = sprintf(
                    'Exchange Reporting "%s". App data is "%s" more than exchange data',
                    $exchangeValue,
                    Subtract::getResult($rounded, $exchangeValue)
                );
            } else {
                $tip = sprintf(
                    'Exchange Reporting "%s". App data is "%s" less than exchange data',
                    $exchangeValue,
                    Subtract::getResult($exchangeValue, $rounded)
                );
            }
        }

        return sprintf(
            "<fg=%s>$%s</>\t(%s)",
            $color,
            number_format((float) $rounded, 2),
            $tip
        );
    }

    /**
     * @param int $month
     * @return string
     */
    private function getExchangeReported1099KMonthValue(int $month): string
    {
        $result = '0';
        $config = Config::getInstance()->get('gemini')->taxes->{"year_$this->year"};
        if ($config) {
            $form = $config->form1099K;
            if ($form) {
                $month = strtolower(date('M', mktime(0, 0, 0, $month, 1)));
                $value = $form->$month;
                if ($value) {
                    $result = $value;
                }
            }
        }
        return $result;
    }

    /**
     * @param array $data
     * @param \ArrayObject $transaction
     */
    private function collectForm8949(array &$data, \ArrayObject $transaction): void
    {
        $data['totalAssetsSold'] = Add::getResult($data['totalAssetsSold'], $transaction['amount']);
        $data['totalProceeds'] = Add::getResult($data['totalProceeds'], $transaction['proceeds']);

        $term = $this->getTerm($transaction['buy_date'], $transaction['sell_date']);
        ++$data[$term]['count'];
        $data[$term]['capital_gain'] = Add::getResult($data[$term]['capital_gain'], $transaction['capital_gain']);
        $data[$term]['cost_basis'] = Add::getResult($data[$term]['cost_basis'], $transaction['cost_basis']);
        $data[$term]['proceeds'] = Add::getResult($data[$term]['proceeds'], $transaction['proceeds']);
        if ($data[$term]['date_purchase'] === null || \strtotime($data[$term]['date_purchase']) > \strtotime($transaction['buy_date'])) {
            $data[$term]['date_purchase'] = $transaction['buy_date'];
        }
        if ($data[$term]['date_sale'] === null || \strtotime($data[$term]['date_sale']) < \strtotime($transaction['sell_date'])) {
            $data[$term]['date_sale'] = $transaction['sell_date'];
        }
    }

    private function getForm8949(array $data): array
    {
        $totalProceeds = '0';
        $output = [\sprintf('<options=bold>Form 8949 - %d</>', $this->year)];
        foreach (\array_keys($data) as $i => $symbol) {
            $output[] = sprintf(($i === 0 ? '' : "\n") . "<options=bold>%s</>", strtoupper($symbol));
            foreach (['short', 'long'] as $term) {
                $output[] = \sprintf("<options=bold>%s Term</>", ucwords($term));
                $output[] = \sprintf("  Trades:\t\t%d", $data[$symbol][$term]['count']);
                $output[] = \sprintf("  Cost Basis:\t%s", $data[$symbol][$term]['cost_basis']);
                $output[] = \sprintf("  Proceeds:\t%s", $data[$symbol][$term]['proceeds']);
                $output[] = \sprintf("  Capital Gains:\t%s", $data[$symbol][$term]['capital_gain']);
                $output[] = \sprintf("  Date Purchase:\t%s", $data[$symbol][$term]['date_purchase']);
                $output[] = \sprintf("  Date Sell:\t%s", $data[$symbol][$term]['date_sale']);

                $totalProceeds = Add::getResult($totalProceeds, $data[$symbol][$term]['proceeds']);
            }
            $output[] = \sprintf("  Total USD Proceeds:\t\t%s", $data[$symbol]['totalProceeds']);
            $output[] = \sprintf("  Total Assets Sold:\t%s", $data[$symbol]['totalAssetsSold']);
        }
        $output[] = \sprintf("\nGross Proceeds: %s", $totalProceeds);
        return $output;
    }

    /**
     * Return if it is a long or short term trade.
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
     * @param OutputInterface $output
     */
    private function outputHeaders(OutputInterface $output): void
    {
        $output->write([
            "\n",
            \str_pad('<options=underscore>Description of Property</>', 26 + 23, ' ', STR_PAD_RIGHT),
            \str_pad('<options=underscore>Date Acquired</>', 16 + 23, ' ', STR_PAD_RIGHT),
            \str_pad('<options=underscore>Date Sold</>', 16 + 23, ' ', STR_PAD_RIGHT),
            \str_pad('<options=underscore>Proceeds</>', 24 + 23, ' ', STR_PAD_RIGHT),
            \str_pad('<options=underscore>Cost Basis</>', 24 + 23, ' ', STR_PAD_RIGHT),
            '<options=underscore>Gain or Loss</>',
            "\n",
        ]);
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
