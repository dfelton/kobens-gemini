<?php

namespace Kobens\Gemini\Taxes;


use Zend\Db\Adapter\Adapter;

final class GetUnsoldBuysFactory implements GetUnsoldBuysFactoryInterface
{
    /**
     * @var GetUnsoldBuys[]
     */
    private $instances = [];

    /**
     * @var Adapter
     */
    private $adapter;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function create(string $symbol): GetUnsoldBuys
    {
        if (!\array_key_exists($symbol, $this->instances)) {
            $this->instances[$symbol] = new GetUnsoldBuys($this->adapter, $symbol);
        }
        return $this->instances[$symbol];
    }
}