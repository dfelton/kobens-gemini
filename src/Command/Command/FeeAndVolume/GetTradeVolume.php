<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolumeInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetTradeVolumeInterface;
use Kobens\Gemini\Helper\NotionalVolumeTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetTradeVolume extends Command
{
    protected static $defaultName = 'fee-volume:get-trade-volume';

    private GetTradeVolumeInterface $volume;

    public function __construct(GetTradeVolumeInterface $getTradeVolumeInterface)
    {
        $this->volume = $getTradeVolumeInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump($this->volume->getVolume());
    }
}
