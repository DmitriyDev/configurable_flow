<?php

namespace App\State;

use Workflow\AbstractState;

class CalculationState extends AbstractState
{
    private $value = 0;

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value)
    {
        $this->value = $value;
    }


}