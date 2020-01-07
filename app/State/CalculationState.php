<?php

namespace App\State;

use Workflow\Interfaces\StateInterface;

class CalculationState implements StateInterface
{
    private $value = 0;

    function history()
    {
        // TODO: Implement history() method.
    }

    function appendToHistory()
    {
        // TODO: Implement appendToHistory() method.
    }


    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value)
    {
        $this->value = $value;
    }


}