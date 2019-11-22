<?php

namespace Kobens\Gemini\Api\Rest;

use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\Exception\Http\RequestTimeoutException;
use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Exception\InvalidResponseException;
use Kobens\Gemini\Exception\LogicException;
use Kobens\Gemini\Exception\ResourceMovedException;
use Kobens\Gemini\Exception\Api\Reason\InvalidNonceException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

abstract class AbstractRequest
{
    private const CURL_CONNECTTIMEOUT = 60;
    private const CURL_TIMEOUT        = 120;
    private const CURL_RETURNTRANSFER = true;
    const CURL_POST = true;

    /**
     * @var ThrottlerInterface
     */
    private $throttler;

    /**
     * @var HostInterface
     */
    private $host;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface
    ) {
        $this->host = $hostInterface;
        $this->throttler = $throttlerInterface;
    }

    abstract protected function getUrlPath(): string;

    abstract protected function getRequestHeaders(): array;

    final protected function getLogger(): Logger
    {
        if (!$this->logger) {
            $this->logger = new Logger($this->getUrlPath());
            $this->logger->pushHandler(new StreamHandler('/tmp/curl_timers.log', Logger::INFO));
        }
        return $this->logger;
    }

    final protected function getResponse(): array
    {
        // TODO: eliminate this iterations bullshit once we implement multi api key features
        $response = null;
        $iterations = 0;
        do {
            $response = $this->_getResponse();
            try {
                $this->_throwResponseException($response);
            } catch (InvalidNonceException $e) {
                ++$iterations;
                $response = null;
            }
        } while ($response === null && $iterations <= 100);
        if ($response === null) {
            throw new \Exception('Max Iterations reached');
        } elseif ($response['code'] !== 200) {
            throw new LogicException(
                'Response code 200 expected',
                $response['code'],
                new \Exception(\json_encode($response))
            );
        }
        return $response;
    }

    final private function _getResponse(): array
    {
        $this->throttler->throttle();

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL,            'https://'.$this->host->getHost().$this->getUrlPath());
        \curl_setopt($ch, CURLOPT_HTTPHEADER,     $this->getRequestHeaders());
        \curl_setopt($ch, CURLOPT_POST,           static::CURL_POST);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, self::CURL_RETURNTRANSFER);
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CURL_CONNECTTIMEOUT);
        \curl_setopt($ch, CURLOPT_TIMEOUT,        self::CURL_TIMEOUT);

        $data = [
            'body' => (string) \curl_exec($ch),
            'code' => (int) \curl_getinfo($ch, CURLINFO_RESPONSE_CODE),
            'curl_errno' => \curl_errno($ch),
            'curl_error' => \curl_error($ch),
            'curlinfo_connect_time' => \curl_getinfo($ch, CURLINFO_CONNECT_TIME),
            'curlinfo_total_time' => \curl_getinfo($ch, CURLINFO_TOTAL_TIME),
        ];

        $this->getLogger()->info(\json_encode([
            'curl_errno' => $data['curl_errno'],
            'curl_error' => $data['curl_error'],
            'curlinfo_connect_time' => $data['curlinfo_connect_time'],
            'curlinfo_total_time' => $data['curlinfo_total_time'],
        ]));

        \curl_close($ch);

        return $data;
    }

    final private function _throwResponseException(array $response): void
    {
        $body = @\json_decode($response['body']); // 504 responses come back as HTML
        switch (true) {
            case $body instanceof \stdClass && \property_exists($body, 'result') && $body->result === 'error':
                $className = "\Kobens\Gemini\Exception\Api\Reason\\{$body->reason}Exception";
                if (!\class_exists($className)) {
                    $className = \Kobens\Gemini\Exception::class;
                }
                throw new $className(
                    $body->message,
                    $response['code'],
                    new \Exception(\json_encode($response))
                );
            case $response['code'] === 0:
                throw new ConnectionException(\sprintf(
                    'Unable to establish a connection with "%s"',
                    $this->host->getHost(),
                    new \Exception(\json_encode($response))
                ));
            case $response['code'] >= 300 && $response['code'] < 400:
                throw new ResourceMovedException(
                    'Resource Has Moved',
                    $response['code'],
                    new \Exception(\json_encode($response))
                );
            case $response['code'] === 408:
                throw new RequestTimeoutException(
                    \sprintf('%s timed out interacting with server.', static::class),
                    new \Exception(\json_encode($response))
                );
            case $response['code'] >= 500:
                throw new InvalidResponseException(
                    $response['body'],
                    $response['code'],
                    new \Exception(\json_encode($response))
                );
            default:
                break;
        }
        $this->throwResponseException($response);
    }

    /**
     * To be overriden in child classes if necessary
     *
     * @param array $response
     */
    protected function throwResponseException(array $response): void
    {
        return;
    }

}
