<?php

namespace Workflow\Interfaces;


use Workflow\HistoryEvent;

interface StateInterface
{
    function history(): array ;

    function appendToHistory(HistoryEvent $event): void;

}