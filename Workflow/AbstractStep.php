<?php

namespace Workflow;

use Workflow\Interfaces\ContextInterface;
use Workflow\Interfaces\StepInterface;

abstract class AbstractStep extends AbstractProcessable implements StepInterface
{

    function init(ContextInterface $context)
    {
        $this->context = $context;
        $this->status = Status::created();
    }


}