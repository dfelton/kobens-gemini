<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api\Rest;

/**
 * Interface ResponseInterface
 * @package Kobens\Gemini\Api\Rest
 * @deprecated
 * @see \Kobens\Core\Http\ResponseInterface
 */
interface ResponseInterface extends \JsonSerializable
{
    public function getBody(): string;

    public function getResponseCode(): int;
}
