<?php

namespace Workflow\Interfaces;


interface StepInterface extends ProcessableInterface
{

    public function init(ContextInterface $context);

}