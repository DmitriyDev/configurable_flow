<?php

namespace App;

use Workflow\Interfaces\ContextInterface;
use Workflow\Interfaces\ProcessableInterface;
use Workflow\Interfaces\ThreadInterface;
use Workflow\Interfaces\StepInterface;

class ThreadFactory
{
 
    private const CONFIG_CONTEXT_KEY = 'context';
    private const CONFIG_CLASS_KEY = 'class';
    private const CONFIG_STEPS_KEY = 'steps';

    /** @var array */
    private $config = [];

    function __construct(string $configFile = null)
    {
        $this->config = $this->getConfig($configFile);
    }

    private function getConfig(string $configFile = null)
    {

        if ($configFile === null) {
            $configFile = self::CONFIG_FILE;
        }
        return \yaml_parse_file($configFile);
    }

    public function getThreadByName(string $threadName, ContextInterface $context): ProcessableInterface
    {
        $threadConfig = $this->extractThreadConfig($threadName);

        return $this->getThreadByConfig($threadConfig, $context);

    }


    private function getThreadByConfig(array $threadConfig, ContextInterface $context): ThreadInterface
    {
        $this->checkContext($threadConfig, $context);

        $threadClassName = $threadConfig[self::CONFIG_CLASS_KEY];

        $steps = $this->extractSteps($threadConfig, $context);

        /** @var ThreadInterface $thread */
        $thread = new $threadClassName();

        $thread->init($context, $steps);

        return $thread;
    }


    private function getStepByConfig(array $stepConfig, ContextInterface $context): StepInterface
    {
        $stepClassName = $stepConfig[self::CONFIG_CLASS_KEY];
        /** @var StepInterface $stepObject */
        $stepObject = new $stepClassName();
        $stepObject->init($context);
        return $stepObject;
    }


    private function extractThreadConfig(string $threadName)
    {
        if (!isset($this->config[$threadName])) {
            throw new \Exception("Thread $threadName not found");
        }

        $threadConfig = $this->config[$threadName];

        $threadClassName = $threadConfig[self::CONFIG_CLASS_KEY];

        if (!class_exists($threadClassName)) {
            throw new \Exception("Thread class $threadClassName not found");
        }

        return $threadConfig;

    }

    private function checkContext(array $threadConfig, ContextInterface $context): void
    {
        $threadContextInterface = $threadConfig[self::CONFIG_CONTEXT_KEY];

        if (!class_exists($threadContextInterface)) {
            throw new \Exception("Invalid expected context interface. Class $threadContextInterface not found");
        }

        if (!$context instanceof $threadContextInterface) {
            throw new \Exception("Invalid context class. Expected interface: $threadContextInterface");
        }
    }

    /**
     * @param string $threadConfig
     * @param ContextInterface $context
     * @return ProcessableInterface[]
     * @throws \Exception
     */
    private function extractSteps(array $threadConfig, ContextInterface $context): array
    {
        $steps = [];
        foreach ($threadConfig[self::CONFIG_STEPS_KEY] as $stepConfig) {

            $stepClassName = $stepConfig[self::CONFIG_CLASS_KEY];

            if (!class_exists($stepClassName)) {
                throw new \Exception("Class $stepClassName dont exists");
            }

            $implementedInterfaces = class_implements($stepClassName);

            if (isset($implementedInterfaces[StepInterface::class])) {
                $steps[] = $this->getStepByConfig($stepConfig, $context);
            }

            if (isset($implementedInterfaces[ThreadInterface::class])) {
                $steps[] = $this->getThreadByConfig($stepConfig, $context);
            }

        }

        return $steps;
    }

}