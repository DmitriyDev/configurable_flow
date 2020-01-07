<?php

namespace App\Steps;


use App\Context\CalculationContextComposite;
use App\State\CalculationState;
use Workflow\Interfaces\ContextInterface;
use Workflow\AbstractStep;
use Workflow\Interfaces\StateInterface;

class BrokenStep extends AbstractStep
{
    protected const NAME = 'Broken step';

    /** @return CalculationContextComposite */
    public function context(): ContextInterface
    {
        return $this->context;
    }

    protected function validState(StateInterface $state): bool
    {
        return $state instanceof CalculationState;
    }


    /** @param CalculationState $state */
    protected function mainProcess(StateInterface $state): void
    {
        throw new \Exception("Step should throw exception");
    }

    public function shouldRun(StateInterface $state): bool
    {
        return true;
    }


}