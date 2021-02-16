<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits;

use Kobens\Core\Config;

trait KillFile
{
    private function killFileExists(string $file): bool
    {
        $filename = Config::getInstance()->getRootDir() . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . $file;
        $exists = file_exists($filename);
        return $exists;
    }
}
