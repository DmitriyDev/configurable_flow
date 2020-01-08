<?php

namespace Workflow\Interfaces;


interface ThreadInterface extends ProcessableInterface
{

    /** @return StepInterface[] */
    public function steps(): array;

    /** @return StepInterface[] */
    public function nextStep();

    public function init(ContextInterface $context, array $steps);

}