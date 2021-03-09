<?php

declare(strict_types=1);

namespace Kobens\Gemini\TradeRepeater;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface UsdProfitsDistributorInterface
{
    public function execute(InputInterface $input, OutputInterface $output): \Generator;
}
