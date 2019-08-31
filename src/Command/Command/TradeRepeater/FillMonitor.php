<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Amp\Loop;
use Amp\Websocket\Client\Handshake;
use Kobens\Gemini\Api\Host;
use Kobens\Gemini\Api\Key;
use Kobens\Gemini\Api\Nonce;
use Kobens\Gemini\TradeRepeater\DataResource\BuyPlaced;
use Kobens\Gemini\TradeRepeater\DataResource\SellPlaced;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class FillMonitor extends Command
{
    protected static $defaultName = 'trade-repeater:fill-monitor';

    private $buyFilled;

    private $sellFilled;

    protected function configure()
    {
        $this->setDescription('Monitors order fillings for the Gemini Trade Repater');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->buyFilled = new BuyPlaced();
        $this->sellFilled = new SellPlaced();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Loop::run(function () use ($output)
        {
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
        });
    }

    private function processMessage(array $msg, OutputInterface $output): void
    {
        switch (true) {
            case $msg['type'] === 'subscription_ack':
                $output->writeln((new \DateTime())->format('Y-m-d H:i:s')."\tSubscription acknowledged.");
                break;
            case $msg['type'] === 'heartbeat':
                $output->writeln((new \DateTime())->format('Y-m-d H:i:s')."\tHeartbeat Received");
                break;
            case $msg['type'] === 'fill' && $msg['remaining_amount'] !== '0':
                $output->writeln(\sprintf(
                    "%s\t<fg=yellow>Partial</> fill of <fg=%s>%s</> order %s. Executed <fg=yellow>%s</>. Remaining amount: <fg=yellow>%s</>",
                    (new \DateTime())->format('Y-m-d H:i:s'),
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
                        $this->buyFilled->setNextState($repeaterId);
                        $output->writeln((new \DateTime())->format('Y-m-d H:i:s')."\tBuy order {$msg['order_id']} on {$msg['symbol']} pair for {$msg['original_amount']} at price of {$msg['price']} filled.");
                    } elseif ($msg['side'] === 'sell') {
                        $this->sellFilled->setNextState($repeaterId);
                        $output->writeln((new \DateTime())->format('Y-m-d H:i:s')."\tSell order {$msg['order_id']} on {$msg['symbol']} pair for {$msg['original_amount']} at price of {$msg['price']} filled.");
                    } else {
                        throw new \Exception("Unhandled side '{$msg['side']}'");
                    }
                }
                break;
            default:
                \Zend\Debug\Debug::dump($msg);
                throw new \Exception('Unhandled Message');
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
        $key = new Key();
        $payload = [
            'request' => '/v1/order/events',
            'nonce' => (new Nonce())->getNonce()
        ];
        $base64Payload = \base64_encode(\json_encode($payload));
        $signature = \hash_hmac('sha384', $base64Payload, $key->getSecretKey());
        return [
            'X-GEMINI-APIKEY'    => $key->getPublicKey(),
            'X-GEMINI-PAYLOAD'   => $base64Payload,
            'X-GEMINI-SIGNATURE' => $signature,
        ];
    }

}
