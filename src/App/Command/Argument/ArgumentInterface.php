<?php

namespace Kobens\Gemini\App\Command\Argument;

interface ArgumentInterface
{
    public function getDefault();

    public function getDescription() : string;

    public function getMode() : int;

    public function getName() : string;
}