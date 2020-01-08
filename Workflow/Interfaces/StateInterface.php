<?php

namespace Workflow\Interfaces;


use Workflow\HistoryEvent;

interface StateInterface
{
    /** @return HistoryEvent[] */
    public function history(): array;

    public function appendToHistory(HistoryEvent $event): void;

    /** @param HistoryEvent[] $event */
    public function mergeHistory(array $event): void;

}