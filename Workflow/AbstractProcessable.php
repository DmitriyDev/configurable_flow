<?php

namespace Workflow;

use Workflow\Interfaces\ContextInterface;
use Workflow\Interfaces\ProcessableInterface;
use Workflow\Interfaces\StateInterface;
use Workflow\Interfaces\StatusInterface;


abstract class AbstractProcessable implements ProcessableInterface
{
    protected const NAME = 'Default';

    /** @var ContextInterface */
    protected $context;

    /** @var StatusInterface */
    protected $status;

    public function name(): string
    {
        return static::NAME;
    }

    public function status(): StatusInterface
    {
        return $this->status;
    }

    public function context(): ContextInterface
    {
        return $this->context;
    }


    public function shouldRun(StateInterface $state): bool
    {
        return true;
    }

    abstract protected function validateState(StateInterface $state): void;

    protected function preRun(StateInterface $state): void
    {
        return;
    }

    abstract protected function mainProcess(StateInterface $state): void;

    protected function postRun(StateInterface $state): void
    {
        return;
    }

    final public function run(StateInterface $state): void
    {
        try {
            $this->validateState($state);

            if (!$this->shouldRun($state)) {
                $this->status = Status::skipped();
                return;

            }

            $this->preRun($state);
            $this->status = Status::inProgress();
            $this->mainProcess($state);
            $this->status = Status::success();
            $this->postRun($state);
        } catch (\Throwable $e) {
            $this->status = Status::failure();
        }

    }

}