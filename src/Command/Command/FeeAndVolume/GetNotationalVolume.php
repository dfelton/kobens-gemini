<?php

namespace Kobens\Gemini\Command\Command\FeeAndVolume;

use Kobens\Gemini\Api\Rest\PrivateEndpoints\FeeAndVolume\GetNotationalVolumeInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetNotationalVolume extends Command
{
    protected static $defaultName = 'fee-volume:get-notational-volume';

    /**
     * @var GetNotationalVolumeInterface
     */
    private $volume;

    public function __construct(GetNotationalVolumeInterface $getNotationalVolumeInterface) {
        $this->volume = $getNotationalVolumeInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = \get_object_vars($this->volume->getVolume());
        $keyLength = 0;
        foreach (\array_keys($data) as $key) {
            if (\strlen($key) > $keyLength) {
                $keyLength = \strlen($key);
            }
        }

        foreach ($data as $key => $val) {
            $key = \ucwords(\str_replace('_', ' ', $key));
            $key = \str_pad($key, $keyLength, ' ', STR_PAD_RIGHT);
            if ($val !== []) {
                $output->writeln("$key\t{$this->getFormattedVal($val)}");
            }
        }
    }

    final private function getFormattedVal($value): string
    {
        switch (true) {
            case $value === true:
                $str = "<fg=green>true</>";
                break;

            case $value === false;
            $str = "<fg=red>false</>";
            break;

            case \is_numeric($value);
            $str = "<fg=yellow>$value</>";
            break;

            case \is_array($value):
                $str = PHP_EOL;
                foreach ($value as $key => $val) {
                    $key = \ucwords(\str_replace('_', ' ', $key));
                    $str .= "\t$key: $val";
                }
                break;

            default:
                $str = "<fg=white>$value</>";
                break;
        }
        return $str;
    }

}
