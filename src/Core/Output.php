<?php

namespace Kobens\Core;

class Output
{
    /**
     * @param string $message
     * @return Output
     */
    public function write(string $message) : Output
    {
        echo $message,PHP_EOL;
        return $this;
    }
    
     /**
      * @param \Exception $e
      * @return Output
      */
    public function writeException(\Exception $e) : Output
    {
        echo $e->getMessage(),PHP_EOL;
        return $this;
    }
}