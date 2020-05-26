<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api;

class Nonce implements NonceInterface
{
    public function getNonce(): string
    {
        $microtime = explode(' ', microtime());
        return $microtime[1] . substr($microtime[0], 2);
    }
}
