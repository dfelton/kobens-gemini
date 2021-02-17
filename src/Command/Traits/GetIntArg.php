<?php

declare(strict_types=1);

namespace Kobens\Gemini\Command\Traits;

use Symfony\Component\Console\Input\InputInterface;

trait GetIntArg
{
    private function getIntArg(InputInterface $input, string $arg, int $default, ?int $min = null, ?int $max = null): int
    {
        $val = (string) $input->getOption($arg);
        if ($val === '') {
            $val = $default;
        } elseif (ctype_digit($val) === false) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" must be an integer value.',
                $arg
            ));
        } else {
            $val = (int) $val;
        }
        if ($min !== null && $val < $min) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" must be greater than or equal to "%d".',
                $arg,
                $min
            ));
        }
        if ($max !== null && $val < $min) {
            throw new \InvalidArgumentException(sprintf(
                '"%s" must be less than or equal to "%d".',
                $arg,
                $max
            ));
        }
        return $val;
    }
}
