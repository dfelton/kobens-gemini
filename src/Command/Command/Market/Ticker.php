<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\Market;

use Kobens\Gemini\Api\Rest\PublicEndpoints\TickerInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Ticker extends Command
{
    protected static $defaultName = 'market:ticker';

    private TickerInterface $ticker;

    public function __construct(
        TickerInterface $tickerInterface
    ) {
        $this->ticker = $tickerInterface;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Outputs details on a market book.');
        $this->addArgument('symbol', InputArgument::OPTIONAL, 'Trading Pair Symbol', 'btcusd');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symbol = $input->getArgument('symbol');
        $data = $this->ticker->getData($symbol);
        $base = \strtoupper(Pair::getInstance($symbol)->getBase()->getSymbol());
        $quote = \strtoupper(Pair::getInstance($symbol)->getQuote()->getSymbol());

        $output->write(PHP_EOL);

        $output->writeln("<options=bold>Symbol:</>\t\t" . strtoupper($symbol));
        unset($symbol);

        $output->writeln("<options=bold>Bid:</>\t\t<fg=green>{$data->bid}</>");
        $output->writeln("<options=bold>Ask:</>\t\t<fg=red>{$data->ask}</>");
        $output->writeln("<options=bold>Last:</>\t\t{$data->last}");
        $output->writeln("<options=bold>24 Hour Volume:</>");
        $output->writeln("\t<options=bold>{$base}</>\t{$data->volume->{$base}}");
        $output->writeln("\t<options=bold>{$quote}</>\t{$data->volume->{$quote}}");

        $output->write(PHP_EOL);
        return 0;
    }
}
