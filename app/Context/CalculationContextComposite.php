<?php

namespace App\Context;

use Workflow\Interfaces\ContextInterface;

class CalculationContextComposite implements ContextInterface
{
    private const INCREMENT_VALUE = 10;
    private const MAXIMUM_VALUE = 100;
    private const MINIMUM_VALUE = 0;

    public function getStepValue(): int
    {
        return self::INCREMENT_VALUE;
    }

    public function getMaximumValue(): int
    {
        return self::MAXIMUM_VALUE;
    }

    public function getMinimumValue(): int
    {
        return self::MINIMUM_VALUE;
    }
}