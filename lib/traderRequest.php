<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'restKeyInterface.php';

abstract class TraderRequest
{
    const REQUEST_URI = '';
    const ENDPOINT_NEW_ORDER                 = '';
    const ENDPOINT_CANCEL_ORDER              = '/v1/order/cancel';
    const ENDPOINT_CANCEL_ALL_SESSION_ORDERS = '/v1/order/cancel/session';
    const ENDPOINT_CANCEL_ALL_ACTIVE_ORDERS  = '/v1/order/cancel/all';
    const ENDPOINT_ORDER_STATUS              = '/v1/order/status';
    const ENDPOINT_GET_ACTIVE_ORDERS         = '/v1/orders';
    const ENDPOINT_GET_PAST_TRADES           = '/v1/mytrades';
    const ENDPOINT_GET_TRADE_VOLUME          = '/v1/tradevolume';
    const ENDPOINT_GET_NOTIONAL_VOLUME       = '/v1/notionalvolume';
    const ENDPOINT_HEARTBEAT                 = '/v1/heartbeat';
    const ENDPOINT_GET_AVAILABLE_BALANCES    = '/v1/balances';

    protected $payload = [];

    protected $response;

    /**
     * @var RestKeyInterface
     */
    protected $restKey;

    public function __construct(
        RestKeyInterface $restKey,
        array $payload = [])
    {
        $this->payload = $payload;
        $this->restKey = $restKey;
    }

    protected function getHeaders()
    {
        $payload = array_merge(
            [
                'request' => static::REQUEST_URI,
                'nonce' => $this->restKey->getNonce()
            ],
            $this->payload
        );
        $base64Payload = base64_encode(json_encode($payload));
        return [
            'Cache-Control: no-cache',
            'Content-Length: 0',
            'Content-Type: text/plain',
            'X-GEMINI-APIKEY: ' . $this->restKey->getPublicKey(),
            'X-GEMINI-PAYLOAD: ' . $base64Payload,
            'X-GEMINI-SIGNATURE: ' . hash_hmac('sha384', $base64Payload, $this->restKey->getSecretKey())
        ];
    }

    final public function makeRequest()
    {
        if (!is_null($this->response)) {
            throw new Exception('Cannot place same request twice.');
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders());

        $this->response = curl_exec($ch);

        curl_close($ch);
    }

    protected function getUrl()
    {
        return $this->restKey->getHost().static::REQUEST_URI;
    }

    final public function getResponse()
    {
        return $this->response;
    }
}