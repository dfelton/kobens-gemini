<?php

namespace Kobens\Db;

use \Zend\Db\Adapter\Adapter as ZendAdapter;

class Adapter
{
    /**
     * @var ZendAdapter
     */
    private $adapter;
    
    public function __construct($config)
    {
        $this->adapter = new ZendAdapter($config);
    }
    
    /**
     * Return a new Sql object 
     * 
     * @return \Zend\Db\Sql\Sql
     */
    public function getSql()
    {
        return new \Zend\Db\Sql\Sql($this->getAdapter());
    }
    
    /**
     * @throws \Exception
     * @return ZendAdapter
     */
    public function getAdapter() : ZendAdapter
    {
        return $this->adapter;
    }
}