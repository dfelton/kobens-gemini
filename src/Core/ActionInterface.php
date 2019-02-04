<?php

namespace Kobens\Core;

interface ActionInterface extends \Kobens\Core\Config\RuntimeInterface
{
    /**
     * @param \Kobens\Core\App $app
     */
    public function __construct(
        \Kobens\Core\App $app
    );
    
    /**
     * @return self
     */
    public function execute() : self;
}