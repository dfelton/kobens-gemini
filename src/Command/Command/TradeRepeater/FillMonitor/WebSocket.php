<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater\FillMonitor;

use Amp\Websocket\Client\Handshake;
use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\BuyPlacedInterface;
use Kobens\Gemini\TradeRepeater\Model\Resource\Trade\Action\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class WebSocket extends Command
{
    protected static $defaultName = 'trade-repeater:fill-monitor-websocket';

    /**
     * @var BuyPlacedInterface
     */
    private $buyPlaced;

    /**
     * @var SellPlacedInterface
     */
    private $sellPlaced;

    /**
     * @var KeyInterface
     */
    private $key;

    /**
     * @var NonceInterface
     */
    private $nonce;

    /**
     * @var EmergencyShutdownInterface
     */
    private $shutdown;

    /**
     * @var HostInterface
     */
    private $host;

    public function __construct(
        EmergencyShutdownInterface $shutdownInterface,
        HostInterface $hostInterface,
        KeyInterface $keyInterface,
        NonceInterface $nonceInterface,
        BuyPlacedInterface $buyPlacedInterface,
        SellPlacedInterface $sellPlacedInterface
    ) {
        parent::__construct();
        $this->shutdown = $shutdownInterface;
        $this->host = $hostInterface;
        $this->key = $keyInterface;
        $this->nonce = $nonceInterface;
        $this->buyPlaced = $buyPlacedInterface;
        $this->sellPlaced = $sellPlacedInterface;
    }

    protected function configure()
    {
        $this->setDescription('Monitors order fillings for the Gemini Trade Repater');
        $this->addOption('reconnect_delay', 'd', InputOption::VALUE_OPTIONAL, 'Time to wait in seconds (min 5 seconds) between reconnection attempts.', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $reconnectDelay = (int) $input->getOption('reconnect_delay');
        if ($reconnectDelay < 5) {
            $reconnectDelay = 5;
        }
        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                \Amp\Loop::run($this->main($output));
            } catch (\Amp\Websocket\Client\ConnectionException $e) {
                $output->writeln([
                    "<fg=red>{$this->now()}\t{$e->getMessage()}</>",
                    "<fg=yellow>{$this->now()}\tSleeping {$reconnectDelay} seconds before next reconnect attempt.</>"
                ]);
                \sleep($reconnectDelay);
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode($e);
            }
        }
        $output->writeln("\n<fg=red>{$this->now()}\tShutdown signal detected.\n");
    }

    private function main(OutputInterface $output): \Closure
    {
        return function() use ($output) {
            /** @var \Amp\Websocket\Client\Connection $connection */
            /** @var \Amp\Websocket\Message $message */
            $connection = yield \Amp\Websocket\Client\connect(
                new Handshake($this->getUrl(), null, $this->getHeaders())
            );
            while ($message = yield $connection->receive()) {
                $payload = yield $message->buffer();
                $data = \json_decode($payload, true);
                if (\strpos($payload, '[') === 0) {
                    foreach ($data as $update) {
                        $this->processMessage($update, $output);
                    }
                } else {
                    $this->processMessage($data, $output);
                }
                if ($this->shutdown->isShutdownModeEnabled()) {
                    \Amp\Loop::stop();
                }
            }
        };
    }

    private function processMessage(array $msg, OutputInterface $output): void
    {
        switch (true) {
            case $msg['type'] === 'subscription_ack':
                $output->writeln($this->now()."\tSubscription acknowledged.");
                break;
            case $msg['type'] === 'heartbeat':
                $output->writeln($this->now()."\tHeartbeat Received");
                break;
            case $msg['type'] === 'fill' && $msg['remaining_amount'] !== '0':
                $output->writeln(\sprintf(
                    "%s\t<fg=yellow>Partial</> fill of <fg=%s>%s</> order %s. Executed <fg=yellow>%s</>. Remaining amount: <fg=yellow>%s</>",
                    $this->now(),
                    $msg['side']==='buy'?'green':'red',
                    $msg['side'],
                    $msg['order_id'],
                    $msg['executed_amount'],
                    $msg['remaining_amount']
                ));
                break;
            case $msg['type'] === 'fill' && $msg['remaining_amount'] === '0' && \array_key_exists('client_order_id', $msg):
                $repeaterId = $this->getRecordId($msg['client_order_id']);
                if ($repeaterId) {
                    switch ($msg['side']) {
                        case 'buy':
                            if ($this->buyPlaced->setNextState($repeaterId)) {
                                $output->writeln($this->now()."\t($repeaterId) <fg=green>Buy</> order {$msg['order_id']} on {$msg['symbol']} pair for {$msg['original_amount']} at price of {$msg['price']} filled.");
                            }
                            break;

                        case 'sell':
                            if ($this->sellPlaced->setNextState($repeaterId)) {
                                $output->writeln($this->now()."\t($repeaterId) <fg=red>Sell</> order {$msg['order_id']} on {$msg['symbol']} pair for {$msg['original_amount']} at price of {$msg['price']} filled.");
                            }
                            break;

                        default:
                            throw new \Exception("Unhandled side '{$msg['side']}'");
                            break;
                    }
                }
                break;
            default:
                throw new \Exception('Unhandled Message: '.\json_encode($msg));
        }
    }

    private function getRecordId(string $clientOrderId): ?int
    {
        $recordId = null;
        if (\strpos($clientOrderId, 'repeater_') === 0) {
            $parts = \explode('_', $clientOrderId);
            $recordId = (int) $parts[1];
        }
        return $recordId;
    }

    private function getUrl(): string
    {
        return 'wss://'.$this->host->getHost().'/v1/order/events?eventTypeFilter=fill';
    }

    private function getHeaders(): array
    {
        $payload = [
            'request' => '/v1/order/events',
            'nonce' => $this->nonce->getNonce()
        ];
        $base64Payload = \base64_encode(\json_encode($payload));
        $signature = \hash_hmac('sha384', $base64Payload, $this->key->getSecretKey());
        return [
            'X-GEMINI-APIKEY'    => $this->key->getPublicKey(),
            'X-GEMINI-PAYLOAD'   => $base64Payload,
            'X-GEMINI-SIGNATURE' => $signature,
        ];
    }

    private function now(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s');
    }
}