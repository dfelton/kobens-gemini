<?php

declare(strict_types=1);

namespace Kobens\Gemini\Api;

interface KeyInterface
{
    public function getSecretKey(): string;

    public function getPublicKey(): string;
}
