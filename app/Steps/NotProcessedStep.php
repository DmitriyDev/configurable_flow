<?php

namespace App\Steps;


use App\Context\CalculationContextComposite;
use App\State\CalculationState;
use Workflow\Interfaces\ContextInterface;
use Workflow\AbstractStep;
use Workflow\Interfaces\StateInterface;

class NotProcessedStep extends AbstractStep
{
    protected const NAME = 'Non-processable step';

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
        throw new \Exception("The step process must be skipped");
    }

    public function shouldRun(StateInterface $state): bool
    {
        return false;
    }


}