<?php
namespace Kobens\Gemini\App\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\App\Config;

trait CommandTraits
{
    protected function sleep(OutputInterface $output = null, int $seconds = 5) : void
    {
        if ($seconds <= 0) {
            return;
        }
        for ($i = $seconds; $i > 0; $i--) {
            if ($output) {
                $output->write('.');
            }
           \sleep(1);
        }
    }

    protected function debugAndSleep(\Exception $e, OutputInterface $output) : void
    {
        $this->clearTerminal();
        $output->writeln([
            $this->getNow(),
            'Host:'.$this->getHost(),
            'Error Class:'.\get_class($e),
            'Error Message:'.$e->getMessage(),
            'Error Code:'.$e->getCode(),
        ]);
        $this->sleep($output, 10);
    }

    protected function clearTerminal() : void
    {
        \system('clear');
    }

    protected function getNow() : string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }

    protected function getHost()
    {
        return (new Config())->gemini->api->host;
    }
}

