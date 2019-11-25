<?php

namespace Kobens\Gemini\Api;


class Host implements HostInterface
{
    /**
     * @var string
     */
    private $host;

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
