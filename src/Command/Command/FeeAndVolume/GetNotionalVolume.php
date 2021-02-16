<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolumeInterface;
use Kobens\Gemini\Helper\NotionalVolumeTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetNotionalVolume extends Command
{
    protected static $defaultName = 'fee-volume:get-notional-volume';

    private GetNotionalVolumeInterface $volume;

    public function __construct(GetNotionalVolumeInterface $getNotationalVolumeInterface)
    {
        $this->volume = $getNotationalVolumeInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        try {
            NotionalVolumeTable::getTable($this->volume->getVolume(), $output)->render();
        } catch (\Throwable $e) {
            $exitCode = 1;
            $output->writeln([
                sprintf('Error Message: %s', $e->getMessage()),
                sprintf('Error Code: %d', $e->getCode()),
                sprintf("Stack Trace:\n%s", $e->getTraceAsString()),
            ]);
        }
        return $exitCode;
    }
}
