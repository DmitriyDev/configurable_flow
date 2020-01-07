<?php

namespace Workflow\Interfaces;


interface ProcessableInterface
{

    public function name(): string;

    public function status(): StatusInterface;

    public function context(): ContextInterface;

    public function shouldRun(StateInterface $state): bool;

    public function run(StateInterface $state): void;

}