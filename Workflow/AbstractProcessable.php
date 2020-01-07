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

    final public function name(): string
    {
        return static::NAME;
    }

    final public function status(): StatusInterface
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

    abstract protected function validState(StateInterface $state): bool;

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
            if(!$this->validState($state))
            {
                throw new \Exception("Invalid state received");
            }

            if ($this->shouldRun($state)) {
                $this->status = Status::inProgress();
                $this->preRun($state);
                $this->mainProcess($state);
                $this->postRun($state);
                $this->status = Status::success();
            } else {
                $this->status = Status::skipped();
            }
        } catch (\Throwable $e) {
            $this->status = Status::failure();
        }

        $event = new HistoryEvent($this, new \DateTime(), $this->status);

        $state->appendToHistory($event);

    }


}