<?php

namespace Kobens\Gemini\Command\Command\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotionalVolumeInterface;
use Kobens\Gemini\Helper\NotionalVolumeTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetNotationalVolume extends Command
{
    protected static $defaultName = 'fee-volume:get-notional-volume';

    /**
     * @var GetNotionalVolumeInterface
     */
    private $volume;

    public function __construct(GetNotionalVolumeInterface $getNotationalVolumeInterface)
    {
        $this->volume = $getNotationalVolumeInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = NotionalVolumeTable::getTable($this->volume->getVolume(), $output);
        $table->render();
    }
}
