<?php

namespace Kobens\Gemini\App\Actions\MarketData;

use Kobens\Core\ActionInterface;
use Kobens\Core\App\ResourcesInterface;
use Kobens\Core\Config\RuntimeInterface;
use Kobens\Gemini\Exchange;
use Zend\Cache\StorageFactory;
use Zend\Cache\Storage\StorageInterface;

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

    public function __construct(ResourcesInterface $resourcesInterface)
    {
        $this->app = $resourcesInterface;
    }

    public function execute() : void
    {
        $exchange = new Exchange(
            $this->getCache(),
            $this->app->getConfig()->gemini->api
        );
        $exchange->getBookKeeper($this->getSymbol())->openBook();
    }

    protected function getSymbol()
    {
        return isset($this->runtimeArgs['market_symbol'])
            ? $this->runtimeArgs['market_symbol']
            : '';
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

    protected function getCache() : StorageInterface
    {
        $cfg = $this->app->getConfig()->get('cache')->toArray();
        return StorageFactory::factory($cfg);
    }

}