<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Gemini\TradeRepeater\Model\Resource\Trade as TradeResource;
use Kobens\Gemini\TradeRepeater\Model\Trade\AddAmount;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class AddToPosition extends Command
{
    protected static $defaultName = 'repeater:add-to';

    private AddAmount $addAmount;

    private TradeResource $tradeResource;

    public function __construct(
        AddAmount $addAmount,
        TradeResource $tradeResource
    ) {
        $this->addAmount = $addAmount;
        $this->tradeResource = $tradeResource;
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'ID of Repeater Record');
        $this->addArgument('amount', InputArgument::REQUIRED, 'Additional Amount to Buy');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->addAmount->add(
            $this->tradeResource->getById((int) $input->getArgument('id'))->getId(),
            $this->getAmount($input)
        );
        return 0;
    }

    private function getAmount(InputInterface $input): string
    {
        $amount = (string) $input->getArgument('amount');
        foreach (strpos($amount, '.') === false ? [$amount] : explode('.', $amount) as $i => $part) {
            if (ctype_digit($part) !== true || $i > 1) {
                throw new \InvalidArgumentException(sprintf('Amount "%s" is not a valid number.', $amount));
            }
        }
        return $amount;
    }
}
