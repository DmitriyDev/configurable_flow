<?php

namespace App\Steps;


use App\Context\CalculationContextComposite;
use App\State\BusinessLogicState;
use App\State\CalculationState;
use Workflow\Interfaces\ContextInterface;
use Workflow\AbstractStep;
use Workflow\Interfaces\StateInterface;

class BusinessLogicMinimumStep extends AbstractStep
{
    protected const NAME = 'Business logic (Min) step';

    /** @return CalculationContextComposite */
    public function context(): ContextInterface
    {
        return $this->context;
    }

    protected function validState(StateInterface $state): bool
    {
        return $state instanceof BusinessLogicState;
    }


    /** @param BusinessLogicState $state */
    protected function mainProcess(StateInterface $state): void
    {

        if ($state->getValue() < $this->context()->getMinimumValue()) {
            $state->setValue($this->context()->getMinimumValue());
        }

    }


}