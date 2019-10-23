<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Amp\Websocket\Client\Handshake;
use Kobens\Core\EmergencyShutdownInterface;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Kobens\Gemini\TradeRepeater\DataResource\BuyPlacedInterface;
use Kobens\Gemini\TradeRepeater\DataResource\SellPlacedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FillMonitor extends Command
{
    protected static $defaultName = 'trade-repeater:fill-monitor';

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

    public function __construct(
        BuyPlacedInterface $buyPlacedInterface,
        SellPlacedInterface $sellPlacedInterface,
        NonceInterface $nonceInterface,
        KeyInterface $keyInterface,
        EmergencyShutdownInterface $shutdownInterface
    ) {
        parent::__construct();
        $this->buyPlaced = $buyPlacedInterface;
        $this->sellPlaced = $sellPlacedInterface;
        $this->key = $keyInterface;
        $this->nonce = $nonceInterface;
        $this->shutdown = $shutdownInterface;
    }

    protected function configure()
    {
        $this->setDescription('Monitors order fillings for the Gemini Trade Repater');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (!$this->shutdown->isShutdownModeEnabled()) {
            try {
                \Amp\Loop::run($this->main($output));
            } catch (\Exception $e) {
                $this->shutdown->enableShutdownMode(\json_encode([
                    'time' => $this->now(),
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'class' => \get_class($e),
                    'trace' => $e->getTraceAsString()
                ]));
            }
        }
        $output->writeln("<fg=red>Shutdown Signal Detected</>");
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
                if ($this->shutdown->isShutdownModeEnabled()) {
                    \Amp\Loop::stop();
                }
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
                    if ($msg['side'] === 'buy') {
                        $this->buyPlaced->setNextState($repeaterId);
                        $output->writeln($this->now()."\t($repeaterId) Buy order {$msg['order_id']} on {$msg['symbol']} pair for {$msg['original_amount']} at price of {$msg['price']} filled.");
                    } elseif ($msg['side'] === 'sell') {
                        $this->sellPlaced->setNextState($repeaterId);
                        $output->writeln($this->now()."\t($repeaterId) Sell order {$msg['order_id']} on {$msg['symbol']} pair for {$msg['original_amount']} at price of {$msg['price']} filled.");
                    } else {
                        throw new \Exception("Unhandled side '{$msg['side']}'");
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
            $parts = explode('_', $clientOrderId);
            $recordId = (int) $parts[1];
        }
        return $recordId;
    }

    private function getUrl(): string
    {
        return 'wss://'.(new Host())->getHost().'/v1/order/events?eventTypeFilter=fill';
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
