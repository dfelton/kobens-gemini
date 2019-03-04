<?php

namespace Kobens\Gemini\App\Actions\MarketData;

use \Kobens\Core\Config\RuntimeInterface;
use \Kobens\Core\ActionInterface;

class BookKeeper implements ActionInterface
{
    const API_ACTION_KEY = 'market:book-keeper';

    /**
     * @var \Kobens\Core\App\ResourcesInterface
     */
    protected $app;

    protected $runtimeArgOptions = [
        'market_symbol' => [
            'required' => true,
            'help' => 'Currency Pair Symbol',
        ]
    ];

    /**
     * @var array
     */
    protected $runtimeArgs = [];

    /**
     * @param \Kobens\Core\App\ResourcesInterface $resourcesInterface
     */
    public function __construct(
        \Kobens\Core\App\ResourcesInterface $resourcesInterface
    ) {
        $this->app = $resourcesInterface;
    }

    /**
     * @return ActionInterface
     */
    public function execute() : ActionInterface
    {

    }

    public function getRuntimeArgOptions() : array
    {
        return $this->runtimeArgOptions;
    }

    /**
     * @throws \Kobens\Core\Exception\RuntimeArgsInvalidException
     * @return RuntimeInterface
     */
    public function setRuntimeArgs(array $args) : RuntimeInterface
    {
        $this->runtimeArgs = $args;
        return $this;
    }
}