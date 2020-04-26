<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest\PrivateEndpoints;

use Kobens\Core\Http\Request\ThrottlerInterface;
use Kobens\Gemini\Api\HostInterface;
use Kobens\Gemini\Api\KeyInterface;
use Kobens\Gemini\Api\NonceInterface;
use Kobens\Gemini\Api\Rest\AbstractRequest;

abstract class AbstractPrivateRequest extends AbstractRequest
{
    /**
     * @var KeyInterface
     */
    private $key;

    /**
     * @var NonceInterface
     */
    private $nonce;

    public function __construct(
        HostInterface $hostInterface,
        ThrottlerInterface $throttlerInterface,
        KeyInterface $keyInterface,
        NonceInterface $nonceInterface
    ) {
        $this->key = $keyInterface;
        $this->nonce = $nonceInterface;
        parent::__construct($hostInterface, $throttlerInterface);
    }

    abstract protected function getPayload(): array;

    /**
     * @return array
     */
    final protected function getRequestHeaders(): array
    {
        $base64Payload = \base64_encode(\json_encode(\array_merge(
            $this->getPayload(),
            ['request' => $this->getUrlPath(),'nonce' => $this->nonce->getNonce()]
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
