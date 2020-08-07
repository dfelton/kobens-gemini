<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\TableGateway\TableGatewayInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\PricePointGenerator as Generator;
use Symfony\Component\Console\Input\InputArgument;

final class PricePointGenerator extends Command
{
    protected static $defaultName = 'trade-repeater:price-point-generator';
    
    /**
     * @var TableGatewayInterface
     */
    private $table;
    
    public function __construct(
        TableGatewayInterface $table
    ) {
        $this->table = $table;
        parent::__construct();
    }
    
    protected function configure()
    {
        $this->addArgument('symbol', InputArgument::REQUIRED, 'Trading Pair Symbol');
        $this->addArgument('buy_amount', InputArgument::REQUIRED, 'Buy Amount per Order');
        $this->addArgument('buy_price_start', InputArgument::REQUIRED, 'Buy Price Start');
        $this->addArgument('buy_price_end', InputArgument::REQUIRED, 'Buy Price End');
        $this->addArgument('increment', InputArgument::REQUIRED, 'Increment amount between buy orders');
        $this->addArgument('sell_after_gain', InputArgument::REQUIRED, 'Sell After Gain (1 = same price as purchase, 2 = 100% gain from purchase price)');
        $this->addArgument('save_amount', InputArgument::OPTIONAL, 'Save Amount', 0);
        $this->addArgument('is_enabled', InputArgument::OPTIONAL, 'Is Enabled', 1);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pair = Pair::getInstance($input->getArgument('symbol'));
        $results = Generator::get(
            $pair, 
            (string) $input->getArgument('buy_amount'), 
            (string) $input->getArgument('buy_price_start'), 
            (string) $input->getArgument('buy_price_end'),
            (string) $input->getArgument('increment'),
            (string) $input->getArgument('sell_after_gain'),
            (string) $input->getArgument('save_amount'),
        );
        $isEnabled = (int) $input->getArgument('is_enabled');
        
        foreach ($results->getPricePoints() as $position) {
            if ($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE) {
                $output->writeln(sprintf(
                    "Inserting %s record for %s buy amount at %s price.\tSell %s at %s price.",
                    $pair->getSymbol(),
                    $position->getBuyAmountBase(),
                    $position->getBuyPrice(),
                    $position->getSellAmountBase(),
                    $position->getSellPrice()
                ));
                $this->table->insert([
                    'is_enabled' => $isEnabled,
                    'status' => 'BUY_READY',
                    'symbol' => $pair->getSymbol(),
                    'buy_amount' => $position->getBuyAmountBase(),
                    'buy_price' => $position->getBuyPrice(),
                    'sell_amount' => $position->getSellAmountBase(),
                    'sell_price' => $position->getSellPrice(),
                ]);
            }
        }
        if ($output->getVerbosity() >= OutputInterface::VERBOSITY_NORMAL) {
            $output->writeln(sprintf(
                'Total of %d records inserted for the %s pair.',
                count($results->getPricePoints()),
                $pair->getSymbol()
            ));
        }
    }
}
