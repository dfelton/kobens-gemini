<?php

namespace Kobens\Core\Config;

interface RuntimeInterface
{
    /**
     * @return array
     */
    public function getRuntimeArgOptions() : array;
    
    /**
     * @throws \Kobens\Core\Exception\RuntimeArgsInvalidException
     * @return RuntimeInterface
     */
    public function setRuntimeArgs(array $args) : RuntimeInterface;
}