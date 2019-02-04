<?php

namespace Kobens\Gemini\Api;

interface KeyInterface
{
    /**
     * @return string
     */
    public function getHost() : string;
    
    /**
     * @return string
     */
    public function getSecretKey() : string;
    
    /**
     * @return string
     */
    public function getPublicKey() : string;
}