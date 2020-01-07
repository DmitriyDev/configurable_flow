<?php

namespace App\Thread;

use App\State\CalculationState;
use Workflow\Interfaces\StateInterface;
use Workflow\AbstractThread;

class CalculationThread extends AbstractThread
{
    protected const NAME = "Calculate thread";

    protected function validState(StateInterface $state): bool
    {
        return $state instanceof CalculationState;
    }


}