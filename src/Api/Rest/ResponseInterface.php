<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest;

interface ResponseInterface extends \JsonSerializable
{
    public function getBody(): string;

    public function getResponseCode(): int;
}
