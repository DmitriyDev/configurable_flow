<?php

namespace Workflow;

use Workflow\Interfaces\ContextInterface;
use Workflow\Interfaces\StatusInterface;
use Workflow\Interfaces\StepInterface;

abstract class AbstractStep extends AbstractProcessable implements StepInterface
{

    /** @var ContextInterface */
    protected $context;

    /** @var StatusInterface */
    protected $status;

    function init(ContextInterface $context)
    {
        $this->context = $context;
        $this->status = Status::created();
    }


    public function status(): StatusInterface
    {
        return $this->status;
    }

    public function context(): ContextInterface
    {
        return $this->context;
    }


}