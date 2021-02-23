<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetTradeVolumeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;

final class GetTradeVolume extends Command
{
    protected static $defaultName = 'fee-volume:get-trade-volume';

    private GetTradeVolumeInterface $volume;

    public function __construct(GetTradeVolumeInterface $getTradeVolumeInterface)
    {
        $this->volume = $getTradeVolumeInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('symbol', InputArgument::OPTIONAL, 'Trading Symbol');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->volume->getVolume();
        if ($data === []) {
            $output->writeln('<fg=red>No trading volume to report</>');
            return 0;
        }

        $symbol = $input->getArgument('symbol');
        if ($symbol) {
            $symbol = \Kobens\Gemini\Exchange\Currency\Pair::getInstance($symbol)->getSymbol();
        } else {
            $symbol = null;
        }

        $headers = [];
        foreach (array_keys(get_object_vars($data[0][0])) as $row) {
            $headers[] = ucwords(str_replace('_', ' ', $row));
        }

        $priorSymbol = null;
        foreach ($data[0] as $row) {
            if ($symbol !== null && $row->symbol !== $symbol) {
                continue;
            }
            if ($priorSymbol === null || $row->symbol !== $priorSymbol) {
                if ($table ?? null) {
                    $table->render();
                }
                $table = new Table($output);
                $table->setHeaders($headers);
                $priorSymbol = $row->symbol;
            }
            $table->addRow(array_values((array) $row));
        }
        if ($table ?? null) {
            $table->render();
        } else {
            $output->writeln('<fg=red>No trading volume to report</>');
        }
        return 0;
    }
}
