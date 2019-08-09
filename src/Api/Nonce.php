<?php

namespace Kobens\Gemini\Api;

class Nonce implements NonceInterface
{
    public function getNonce(): string
    {
        // TODO ensure max of 600 per second by using db table 'nonce'
        $microtime = explode(' ', microtime());
        return $microtime[1].substr($microtime[0], 2);
    }
}
