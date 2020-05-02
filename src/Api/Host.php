<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api;

class Host implements HostInterface
{
    private string $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function __toString(): string
    {
        return $this->getHost();
    }
}
