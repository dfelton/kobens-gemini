<?php

namespace Kobens\Gemini\Taxes;

use Zend\Db\Adapter\Adapter;

interface GetUnsoldBuysFactoryInterface
{
    public function create(Adapter $adapter, string $symbol): GetUnsoldBuys;
}