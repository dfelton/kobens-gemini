<?php

namespace Kobens\Gemini\Command\Command\Market;

use Kobens\Gemini\Command\Argument\Symbol;
use Kobens\Gemini\Command\Traits\GetSymbol;
use Kobens\Gemini\Command\Traits\Traits;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class Ticker extends Command
{
    use GetSymbol, Traits;

    protected static $defaultName = 'gemini:market:ticker';

    protected function configure()
    {
        $this->setDescription('Outputs details on a market book.');
        $this->addArgList([new Symbol()], $this);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symbol = $this->getSymbol($input)->getValue();
        $ticker = new \Kobens\Gemini\Api\Rest\Request\Market\Ticker($symbol);
        $response = $ticker->getResponse();
        $d =  \json_decode($response['body'], true);
        unset ($d['volume']['timestamp']);

        $output->write(PHP_EOL);

        $output->writeln("<options=bold>Symbol:</>\t\t".strtoupper($symbol));
        unset ($symbol);

        $output->writeln("<options=bold>Bid:</>\t\t<fg=green>{$d['bid']}</>");
        $output->writeln("<options=bold>Ask:</>\t\t<fg=red>{$d['ask']}</>");
        $output->writeln("<options=bold>Last:</>\t\t{$d['last']}");
        $output->writeln("<options=bold>24 Hour Volume:</>");
        foreach ($d['volume'] as $symbol => $volume) {
            $output->writeln("\t<options=bold>$symbol</>\t$volume");
        }

        $output->write(PHP_EOL);
    }

}
