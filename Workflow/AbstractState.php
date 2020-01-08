<?php

namespace Workflow;

use Workflow\Interfaces\StateInterface;

abstract class AbstractState implements StateInterface
{

    /** @var HistoryEvent[] */
    protected $history;

    /** @return HistoryEvent[] */
    final public function history(): array
    {
        return $this->history;
    }

    final public function appendToHistory(HistoryEvent $event): void
    {
        $this->history[] = $event;
    }

    /** @param HistoryEvent[] $historyLog */
    final public function mergeHistory(array $historyLog): void
    {
        $this->history = \array_merge($this->history, $historyLog);
    }


}