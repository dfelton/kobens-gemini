<?php

namespace Kobens\Core;

use Kobens\Gemini\Api\Rest\Request\Order\Placement\{CancelAll as OrderCancelAll, NewOrder as OrderNew};

final class App
{
    /**
     * @var array
     */
    protected $actionClassMap = [
        OrderNew::API_ACTION_KEY => OrderNew::class,
        OrderCancelAll::API_ACTION_KEY => OrderCancelAll::class,
    ];

    /**
     * @var \Kobens\Core\ActionInterface
     */
    protected $action;

    /**
     * @var \Kobens\Db\Adapter
     */
    protected $db;

    /**
     * @var \Zend\Config\Config
     */
    protected $config;

    /**
     * @var \CliArgs\CliArgs
     */
    protected $cli;

    /**
     * @var \Kobens\Core\Output
     */
    protected $output;

    /**
     * @var array
     */
    protected $cliArgs = [
        'help' => [
            'alias' => 'h',
            'help' => 'Show help about all options',
        ],
        'action' => [
            'alias' => 'a',
            'help' => 'What action to perform. Use "showActions" to list valid actions',
        ],
        'config' => [
            'alias' => 'c',
            'help' => 'What config file to load environment settings from (production|sandbox)',
        ],
    ];

    public function __construct()
    {
        $this->cli = new \CliArgs\CliArgs($this->cliArgs);
        $this->db = new \Kobens\Db\Adapter($this->getConfig()->database->toArray());
        $this->output = new \Kobens\Core\Output();
    }

    final public function run()
    {
        if ($this->cli->isFlagExist('h')) {
            $this->output->write($this->cli->getHelp());
        } else {
            try {
                $action = $this->getAction();
                if ($action->getRuntimeArgOptions()) {
                    $args = new \CliArgs\CliArgs($action->getRuntimeArgOptions());
                    $action->setRuntimeArgs($args->getArgs());
                }
                $action->execute();
            } catch (\Kobens\Gemini\Exception\InvalidPayloadException $e) {
                $this->output->write($e->getMessage());
            } catch (\Exception $e) {
                $this->output->writeException($e);
            }
        }
    }

    /**
     * @throws Exception\ActionRequiredException
     * @throws Exception\ActionInvalidException
     * @return \Kobens\Core\ActionInterface
     */
    protected function getAction() : \Kobens\Core\ActionInterface
    {
        $action = (string) $this->cli->getArg('action');
        $action = trim($action);
        if ($action === '') {
            throw new Exception\ActionRequiredException();
        } elseif (!\array_key_exists($action, $this->actionClassMap)) {
            throw new Exception\ActionInvalidException($action);
        }
        return new $this->actionClassMap[$action]($this);
    }

    /**
     * @return \Zend\Config\Config
     */
    public function getConfig()
    {
        if (is_null($this->config)) {
            $reader = new \Zend\Config\Reader\Xml();
            $filename = (string) $this->cli->getArg('config');
            $array = $reader->fromFile($filename);
            $this->config = new \Zend\Config\Config($array);
        }
        return $this->config;
    }

    /**
     * @return \Kobens\Core\Output
     */
    public function getOutput() : \Kobens\Core\Output
    {
        return $this->output;
    }

    /**
     * @return \Kobens\Db\Adapter
     */
    public function getDb() : \Kobens\Db\Adapter
    {
        return $this->db;
    }
}