<?php

namespace Workflow\Interfaces;


interface StateInterface
{
    function history();

    function appendToHistory();

}