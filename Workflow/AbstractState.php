<?php

namespace Workflow;

use Workflow\Interfaces\StateInterface;

abstract class AbstractState implements StateInterface
{

    /** @var HistoryEvent[] */
    protected $history;

    function history(): array
    {
        return $this->history;
    }

    function appendToHistory(HistoryEvent $event): void
    {
        $this->history[] = $event;
    }

    /** @param HistoryEvent[] $historyLog */
    function mergeHistory(array $historyLog): void
    {
        $this->history = \array_merge($this->history, $historyLog);
    }


}