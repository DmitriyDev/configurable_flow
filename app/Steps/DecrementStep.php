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

    protected function validateState(StateInterface $state): void
    {
        if (!$state instanceof CalculationState) {
            throw new \Exception("DecrementStep works with " . CalculationState::class . " only");
        }
    }


    /** @param CalculationState $state */
    protected function mainProcess(StateInterface $state): void
    {
        $stepValue = $this->context()->getStepValue();
        $newValue = $state->getValue() - $stepValue;
        $state->setValue($newValue);

    }


}