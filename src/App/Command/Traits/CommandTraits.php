<?php
namespace Kobens\Gemini\App\Command\Traits;

use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Gemini\App\Config;
use Kobens\Gemini\App\Command\Argument\ArgumentInterface;
use Symfony\Component\Console\Command\Command;

trait CommandTraits
{
    protected function addArgList(array $args, Command $command) : void
    {
        foreach ($args as $arg) {
            if (!$arg instanceof ArgumentInterface) {
                throw new \Exception('"%s" only accepts objects of the "%s" interface');
            }
            $command->addArgument($arg->getName(), $arg->getMode(), $arg->getDescription(), $arg->getDefault());
        }
    }

    protected function sleep(OutputInterface $output = null, int $seconds = 5) : void
    {
        if ($seconds <= 0) {
            return;
        }
        for ($i = $seconds; $i > 0; $i--) {
            if ($output && !$output->isQuiet()) {
                $output->write('.');
            }
           \sleep(1);
        }
    }

    protected function debugAndSleep(\Exception $e, OutputInterface $output) : void
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

    protected function clearTerminal(OutputInterface $output) : void
    {
        $output->write(chr(27).chr(91).'H'.chr(27).chr(91).'J');
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

