<?php

namespace App\Steps;


use App\Context\CalculationContextComposite;
use App\State\CalculationState;
use Workflow\Interfaces\ContextInterface;
use Workflow\AbstractStep;
use Workflow\Interfaces\StateInterface;

class DecrementStep extends AbstractStep
{
    protected const NAME = 'Decrement step';

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
        $stepValue = $this->context()->getStepValue();
        $newValue = $state->getValue() - $stepValue;
        $state->setValue($newValue);

    }


}