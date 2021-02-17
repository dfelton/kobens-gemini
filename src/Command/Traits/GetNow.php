<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits;

trait GetNow
{
    public function getNow(string $format = 'Y-m-d H:i:s'): string
    {
        return (new \DateTime())->format($format);
    }
}
