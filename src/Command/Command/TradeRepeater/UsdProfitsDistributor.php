<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Symfony\Component\Console\Command\Command;
use Kobens\Gemini\TradeRepeater\UsdProfitsDistributorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Gemini\Command\Traits\Output;
use Kobens\Gemini\Command\Traits\TradeRepeater\ExitProgram;

final class UsdProfitsDistributor extends Command
{
    use Output;
    use ExitProgram;

    private const KILL_FILE = 'kill_usd_profits_dist';

    protected static $defaultName = 'repeater:usd-profits-dist';

    private EmergencyShutdownInterface $shutdown;

    private UsdProfitsDistributorInterface $usdProfitsDistributor;

    public function __construct(
        UsdProfitsDistributorInterface $usdProfitsDistributor,
        EmergencyShutdownInterface $shutdown
    ) {
        $this->usdProfitsDistributor = $usdProfitsDistributor;
        $this->shutdown = $shutdown;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        try {
            foreach ($this->usdProfitsDistributor->execute($input, $output) as $msg) {
                switch ($msg['type'] ?? '') {
                    case 'success':
                        $this->writeSuccess($msg['message'] ?? '', $output);
                        break;

                    case 'warning':
                        $this->writeWarning($msg['message'] ?? '', $output);
                        break;

                    case 'error':
                        $this->writeError($msg['message'] ?? '', $output);
                        break;

                    case 'notice':
                    default:
                        $this->writeNotice($msg['message'] ?? '', $output);
                }
            }
        } catch (\Throwable $e) {
            $this->shutdown->enableShutdownMode($e);
            $exitCode = 1;
        }
        $this->outputExit($output, $this->shutdown, self::KILL_FILE);
        return $exitCode;
    }
}
