<?php

interface RestKeyInterface
{
    public function getHost();
    public function getSecretKey();
    public function getPublicKey();
    public function getNonce();
}