<?php

namespace Workflow;

use Workflow\Interfaces\ContextInterface;
use Workflow\Interfaces\StateInterface;
use Workflow\Interfaces\ThreadInterface;
use Workflow\Interfaces\StepInterface;


abstract class AbstractThread extends AbstractProcessable implements ThreadInterface
{

    /** @var StepInterface[] */
    protected $steps;

    function init(ContextInterface $context, array $steps)
    {
        $this->context = $context;
        $this->steps = $steps;
        $this->status = Status::created();
    }


    public function steps(): array
    {
        return $this->steps;
    }

    public function nextStep()
    {
        foreach ($this->steps() as $step) {
            yield $step;
        }
    }

    protected function mainProcess(StateInterface $state): void
    {
        /** @var StepInterface $step */
        foreach ($this->nextStep() as $step)
        {
            $step->run($state);
        }
    }



}