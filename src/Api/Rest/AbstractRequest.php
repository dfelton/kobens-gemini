<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest;

use Kobens\Core\Exception\ConnectionException;
use Kobens\Core\Exception\Http\RequestTimeoutException;
use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Exception\InvalidResponseException;
use Kobens\Gemini\Exception\LogicException;
use Kobens\Gemini\Exception\ResourceMovedException;

abstract class AbstractRequest
{
    private const   CURL_CONNECTTIMEOUT = 60;
    private const   CURL_TIMEOUT        = 120;
    private const   CURL_RETURNTRANSFER = true;
    protected const CURL_POST           = true;

    /**
     * @var ThrottlerInterface
     */
    private $throttler;

    /**
     * @var HostInterface
     */
    private $host;

    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface
    ) {
        $this->host = $hostInterface;
        $this->throttler = $throttlerInterface;
    }

    abstract protected function getUrlPath(): string;

    abstract protected function getRequestHeaders(): array;

    final protected function getResponse(): Response
    {
        $response = $this->_getResponse();
        $this->_throwResponseException($response);
        if ($response->getResponseCode() !== 200) {
            throw new LogicException(
                'Response code 200 expected',
                $response->getResponseCode(),
                new \Exception(\json_encode($response))
            );
        }
        return $response;
    }

    final private function _getResponse(): Response
    {
        $this->throttler->throttle();

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, 'https://' . $this->host->getHost() . $this->getUrlPath());
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getRequestHeaders());
        \curl_setopt($ch, CURLOPT_POST, static::CURL_POST);
        \curl_setopt($ch, CURLOPT_RETURNTRANSFER, self::CURL_RETURNTRANSFER);
        \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::CURL_CONNECTTIMEOUT);
        \curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);

        $data = [
            'body' => (string) \curl_exec($ch),
            'response_code' => (int) \curl_getinfo($ch, CURLINFO_RESPONSE_CODE),
            'curl_errno' => \curl_errno($ch),
            'curl_error' => \curl_error($ch),
            'curlinfo_connect_time' => \curl_getinfo($ch, CURLINFO_CONNECT_TIME),
            'curlinfo_total_time' => \curl_getinfo($ch, CURLINFO_TOTAL_TIME),
        ];

        \curl_close($ch);

        if ($data['curl_errno'] !== CURLE_OK) {
            $json = (string) json_encode($data);
            throw new \Exception(
                'Curl Error',
                $data['curl_errno'],
                $json ? new \Exception($json) : null
            );
        }

        return new Response($data['body'], $data['response_code']);
    }

    final private function _throwResponseException(Response $response): void
    {
        $body = @\json_decode($response->getBody()); // 504 responses come back as HTML
        switch (true) {
            case $body instanceof \stdClass && ($body->result ?? null) === 'error' && $body->reason ?? null:
                $className = "\Kobens\Gemini\Exception\Api\Reason\{$body->reason}Exception";
                if (!\class_exists($className)) {
                    $className = \Kobens\Gemini\Exception::class;
                }
                throw new $className(
                    $body->message,
                    $response->getResponseCode(),
                    new \Exception(\json_encode($response))
                );
            case $response->getResponseCode() === 0:
                throw new ConnectionException(\sprintf(
                    'Unable to establish a connection with "%s"',
                    $this->host->getHost(),
                    new \Exception(\json_encode($response))
                ));
            case $response->getResponseCode() >= 300 && $response->getResponseCode() < 400:
                throw new ResourceMovedException(
                    'Resource Has Moved',
                    $response->getResponseCode(),
                    new \Exception(\json_encode($response))
                );
            case $response->getResponseCode() === 408:
                throw new RequestTimeoutException(
                    \sprintf('%s timed out interacting with server.', static::class),
                    new \Exception(\json_encode($response))
                );
            case $response->getResponseCode() >= 500:
                throw new InvalidResponseException(
                    $response->getBody(),
                    $response->getResponseCode(),
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
    protected function throwResponseException(Response $response): void
    {
        return;
    }
}
