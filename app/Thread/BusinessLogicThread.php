<?php

namespace App\Thread;

use App\State\BusinessLogicState;
use App\State\CalculationState;
use Workflow\Interfaces\StateInterface;
use Workflow\AbstractThread;

class BusinessLogicThread extends AbstractThread
{
    protected const NAME = "Business logic thread";

    protected function validState(StateInterface $state): bool
    {
        return $state instanceof CalculationState;
    }

    /** @var CalculationState $state */
    protected function mainProcess(StateInterface $state): void
    {
        $businessState = new BusinessLogicState();
        $businessState->setValue($state->getValue());

        parent::mainProcess($businessState);

        $state->setValue($businessState->getValue());
        $state->mergeHistory($businessState->history());
    }


}