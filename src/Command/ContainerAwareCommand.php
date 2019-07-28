<?php

namespace Kobens\Gemini\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ContainerAwareCommand
 * @package Kobens\Gemini\Command
 */
abstract class ContainerAwareCommand extends Command
{
    /**
     * @var ContainerBuilder
     */
    public $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    public function find(string $name)
    {
        return $this->container->get($name);
    }
}