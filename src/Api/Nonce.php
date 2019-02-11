<?php

namespace Kobens\Gemini\Api;

class Nonce implements NonceInterface
{
    /**
     * @var \Kobens\Core\Db\Adapter
     */
    protected $db;

    /**
     * @param \Kobens\Core\Db\Adapter $db
     */
    public function __construct(
        \Kobens\Core\Db\Adapter $db
    ) {
        $this->db = $db;
    }

    /**
     * {@inheritDoc}
     * @see \Kobens\Gemini\Api\NonceInterface::getNonce()
     */
    public function getNonce() : string
    {
        // TODO ensure max of 600 per second by using db table 'nonce'
        $microtime = explode(' ', microtime());
        return $microtime[1].substr($microtime[0], 2);
    }
}