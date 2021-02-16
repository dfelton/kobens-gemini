<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Gemini\Api\Market\GetPriceInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderPlacement\CancelOrderInterface;
use Kobens\Gemini\Api\Rest\PrivateEndpoints\OrderStatus\OrderStatusInterface;
use Kobens\Gemini\Exchange\Currency\Pair;
use Kobens\Gemini\TradeRepeater\Model\Trade;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Update;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Math\BasicCalculator\Divide;
use Kobens\Math\BasicCalculator\Multiply;
use Kobens\Math\BasicCalculator\Subtract;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\TableGatewayInterface;

final class Disable extends Command
{
    /**
     * Percent buffer threshold to avoid canceling close to current market price.
     * Safety measure for potential errors during time of high volatility.
     *
     * @var string
     */
    private string $bufferThreshold;

    private Adapter $adapter;

    private OrderStatusInterface $orderStatus;

    private CancelOrderInterface $cancelOrder;

    private TableGatewayInterface $tblTradeRepeater;

    private \Kobens\Gemini\TradeRepeater\Model\Resource\Trade $tradeResource;

    private EmergencyShutdownInterface $shutdown;

    private GetPriceInterface $getPrice;

    private Update $update;

    public function __construct(
        OrderStatusInterface $orderStatus,
        CancelOrderInterface $cancelOrder,
        Adapter $adapter,
        \Kobens\Gemini\TradeRepeater\Model\Resource\Trade $tradeResource,
        EmergencyShutdownInterface $shutdown,
        GetPriceInterface $getPrice,
        Update $update,
        string $bufferThreshold = '2'
    ) {
        $this->orderStatus = $orderStatus;
        $this->cancelOrder = $cancelOrder;
        $this->tblTradeRepeater = new TableGateway('trade_repeater', $adapter);
        $this->tradeResource = $tradeResource;
        $this->adapter = $adapter;
        $this->shutdown = $shutdown;
        $this->getPrice = $getPrice;
        $this->update = $update;
        $this->bufferThreshold = $bufferThreshold;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('repeater:disable');
        $this->addArgument('pair', InputArgument::REQUIRED, 'Trading pair to target for disabling');
        $this->addArgument('price-from', InputArgument::REQUIRED, 'Price from');
        $this->addArgument('price-to', InputArgument::REQUIRED, 'Price to');
        $this->addOption('force', 'f', InputOption::VALUE_OPTIONAL, sprintf('Force disabling even if within %s%% of current market prices or partially executed.', $this->bufferThreshold), '0');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pair = Pair::getInstance($input->getArgument('pair'));
        $force = $input->getOption('force') === '1';
        $priceFrom = $input->getArgument('price-from');
        $priceTo = $input->getArgument('price-to');
        foreach ($this->getTradeIds($pair, $priceFrom, $priceTo) as $id) {
            $this->adapter->getDriver()->getConnection()->beginTransaction();
            try {
                $this->disable($output, $id, $force);
                $this->adapter->getDriver()->getConnection()->commit();
            } catch (\Exception $e) {
                $this->adapter->getDriver()->getConnection()->rollback();
                $this->shutdown->enableShutdownMode($e);
                break;
            }
        }
        return 0;
    }

    private function disable(OutputInterface $output, int $id, bool $force = false): void
    {
        $trade = $this->tradeResource->getById($id, true);
        if ($force === false && $this->isSafeToCancel($trade) === false) {
            $output->writeln(sprintf('<fg=yellow>Skipping repeater ID %d, buy price %s</>', $trade->getId(), $trade->getBuyPrice()));
            return;
        }
        $result = $this->cancelOrder->cancel($trade->getBuyOrderId());
        if ($result->is_cancelled !== true) {
            throw new \Exception(sprintf('Order %d for repeater record %d not cancelled', $trade->getBuyOrderId(), $trade->getId()));
        }
        if ($force === false && $result->executed_amount !== '0') {
            throw new \Exception(sprintf('Order %d for repeater record %d partially executed.', $trade->getBuyOrderId(), $trade->getId()));
        }
        $this->update->setData(
            [
                'is_enabled' => 0,
                'status' => 'DISABLED',
                'buy_client_order_id' => null,
                'buy_order_id' => null,
                'meta' => null,
            ],
            $trade->getId()
        );
        $pair = Pair::getInstance($trade->getSymbol());
        $base = strtoupper($pair->getBase()->getSymbol());
        $quote = strtoupper($pair->getQuote()->getSymbol());
        $output->writeln(sprintf(
            'DISABLED: Repeater ID %d, buy %s %s @ %s %s/%s',
            $trade->getId(),
            $trade->getBuyAmount(),
            $base,
            $trade->getBuyPrice(),
            $base,
            $quote
        ));
    }

    private function isSafeToCancel(Trade $trade): bool
    {
        if ($this->isMarketPriceOverBufferThreshold($trade) === false) {
            return false;
        }

        if ($trade->getBuyOrderId() === null) {
            throw new \LogicException('Trade ID is null', 0, new \Exception(serialize($trade)));
        }

        return $this->orderStatus->getStatus($trade->getBuyOrderId())->executed_amount === '0';
    }

    private function isMarketPriceOverBufferThreshold(Trade $trade): bool
    {
        $marketPrice = $this->getPrice->getBid($trade->getSymbol());
        $diff = Subtract::getResult($marketPrice, $trade->getBuyPrice());
        $percent = Multiply::getResult(Divide::getResult($diff, $marketPrice, 5), '100');
        return Compare::getResult($percent, $this->bufferThreshold) === Compare::LEFT_GREATER_THAN;
    }


    /**
     * @return int[]
     */
    private function getTradeIds(Pair $pair, string $priceFrom, string $priceTo): array
    {
        $data = $this->tblTradeRepeater->select(function (Select $select) use ($pair): void {
            $select->where->equalTo('symbol', $pair->getSymbol());
            $select->where->equalTo('status', 'BUY_PLACED');
            $select->where->equalTo('is_error', 0);
            $select->where->equalTo('is_enabled', 1);
            $select->columns(['id', 'buy_price']);
        });
        $ids = [];
        foreach ($data as $row) {
            $from = Compare::getResult($priceFrom, $row->buy_price);
            $to = Compare::getResult($priceTo, $row->buy_price);
            if (
                $from === Compare::EQUAL ||
                $to === Compare::EQUAL ||
                ($from === Compare::RIGHT_GREATER_THAN && $to === Compare::LEFT_GREATER_THAN)
            ) {
                $ids[] = (int) $row->id;
            }
        }
        return $ids;
    }
}
