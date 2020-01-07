<?php
namespace App\Context;

use Workflow\Interfaces\ContextInterface;

class CalculationContextComposite implements ContextInterface
{
    private const INCREMENT_VALUE = 10;


    public function getStepValue(): int
    {
        return self::INCREMENT_VALUE;
    }
}