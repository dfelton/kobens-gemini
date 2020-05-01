<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints;

use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Kobens\Gemini\Api\Helper\ResponseHandler;
use Kobens\Gemini\Api\Rest\Response;
use Kobens\Gemini\Api\Rest\ResponseInterface;

/**
 *
 */
final class Request implements RequestInterface
{
    private KeyInterface $key;

    private NonceInterface $nonce;

    private ThrottlerInterface $throttler;

    private HostInterface $host;

    private ResponseHandler $responseHandler;

    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface,
        KeyInterface $keyInterface,
        NonceInterface $nonceInterface,
        ResponseHandler $responseHandler
    ) {
        $this->host = $hostInterface;
        $this->key = $keyInterface;
        $this->nonce = $nonceInterface;
        $this->throttler = $throttlerInterface;
        $this->responseHandler = $responseHandler;
    }

    public function getResponse(string $urlPath): ResponseInterface
    {
        $this->throttler->throttle();

        $ch = \curl_init();

        \curl_setopt($ch, CURLOPT_URL, 'https://' . $this->host->getHost() . $urlPath);
        \curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getRequestHeaders($urlPath));
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
            foreach ($data as $k => $v) {
                if (@json_encode([$k => $v]) === false) {
                    unset($data[$k]);
                }
            }

            $json = (string) json_encode($data);
            throw new \Exception(
                'Curl Error',
                $data['curl_errno'],
                $json ? new \Exception($json) : null
            );
        }

        return new Response($data['body'], $data['response_code']);
    }
    }

    final protected function getRequestHeaders(string $urlPath, array $payload): array
    {
        $base64Payload = \base64_encode(\json_encode(\array_merge(
            $payload,
            [
                'request' => $urlPath,
                'nonce' => $this->nonce->getNonce(),
            ]
        )));
        return [
            'cache-control:no-cache',
            'content-length:0',
            'content-type:text/plain',
            'x-gemini-apikey:' . $this->key->getPublicKey(),
            'x-gemini-payload:' . $base64Payload,
            'x-gemini-signature:' . \hash_hmac('sha384', $base64Payload, $this->key->getSecretKey()),
        ];
    }
}
