<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\CancelFactoryInterface;
use Kobens\Gemini\Api\Rest\Request\Order\Placement\NewOrder\ForceMaker;
use Kobens\Gemini\TradeRepeater\DataResource\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SellPriceAuditor extends Command
{
    protected static $defaultName = 'trade-repeater:price-auditor-sell';

    /**
     * @var SellPlacedInterface
     */
    private $sellPlaced;

    /**
     * @var CancelFactoryInterface
     */
    private $cancelFactory;

    public function __construct(
        CancelFactoryInterface $cancelFactoryInterface,
        SellPlacedInterface $sellPlacedInterface,
        ForceMaker $forceMaker
    ) {
        $this->cancelFactory = $cancelFactoryInterface;
        $this->sellPlaced = $sellPlacedInterface;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rows = $this->sellPlaced->getHealthyRecords();
        foreach ($rows as $row) {
            if ($this->shouldReset($row->sell_price, \json_decode($row->meta)->sell_price)) {

            }
        }
    }

    private function shouldReset(string $originalPrice, string $sellPrice): bool
    {
        if ($originalPrice === $sellPrice) {
            return false;
        }


    }

    private function getMarketSellPrice()
    {

    }

}
