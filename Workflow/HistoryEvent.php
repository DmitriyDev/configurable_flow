<?php

namespace Workflow;


use Workflow\Interfaces\ProcessableInterface;
use Workflow\Interfaces\StatusInterface;

class HistoryEvent
{
    /** @var ProcessableInterface */
    protected $event;

    /** @var StatusInterface */
    protected $status;

    /** @var \DateTime */
    protected $time;

    public function __construct(ProcessableInterface $event, \DateTime $time, StatusInterface $status)
    {
        $this->event = $event;
        $this->time = $time;
        $this->status = $status;
    }

    public function getEvent(): ProcessableInterface
    {
        return $this->event;
    }

    public function getStatus(): StatusInterface
    {
        return $this->status;
    }

    public function getTime(): \DateTime
    {
        return $this->time;
    }


}