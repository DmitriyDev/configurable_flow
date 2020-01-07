<?php

namespace App\Steps;


use App\Context\CalculationContextComposite;
use App\State\CalculationState;
use Workflow\Interfaces\ContextInterface;
use Workflow\AbstractStep;
use Workflow\Interfaces\StateInterface;

class IncrementStep extends AbstractStep
{
    protected const NAME = 'Increment step';

    /** @return CalculationContextComposite */
    public function context(): ContextInterface
    {
        return $this->context;
    }

    protected function validateState(StateInterface $state): void
    {
        if (!$state instanceof CalculationState) {
            throw new \Exception("IncrementStep works with " . CalculationState::class . " only");
        }
    }


    /** @param CalculationState $state */
    protected function mainProcess(StateInterface $state): void
    {

        $stepValue = $this->context()->getStepValue();
        $newValue = $state->getValue() + $stepValue;
        $state->setValue($newValue);

    }


}