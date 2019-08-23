<?php

namespace Kobens\Gemini\Api;

interface KeyInterface
{
    public function getSecretKey(): string;

    public function getPublicKey(): string;
}
