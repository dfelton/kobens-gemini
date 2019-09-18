<?php

namespace Kobens\Gemini\Command\Traits;

use Kobens\Core\Command\Traits\Traits as CoreTraits;
use Kobens\Gemini\Api\Host;
use Symfony\Component\Console\Output\OutputInterface;

trait Traits
{
    use CoreTraits;

    protected function debugAndSleep(\Exception $e, OutputInterface $output): void
    {
        $this->clearTerminal($output);
        if (!$output->isQuiet()) {
            $output->writeln([
                $this->getNow(),
                'Host:'.$this->getHost(),
                'Error Class:'.\get_class($e),
                'Error Message:'.$e->getMessage(),
                'Error Code:'.$e->getCode(),
            ]);
        }
        $this->sleep($output, 10);
    }

    protected function getHost(): string
    {
        return (string) (new Host());
    }
}
