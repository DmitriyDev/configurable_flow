<?php

namespace Workflow\Interfaces;


interface StatusInterface
{

    public static function success(): StatusInterface;

    public static function failure(): StatusInterface;

    public static function created(): StatusInterface;

    public static function inProgress(): StatusInterface;

    public function isSuccess(): bool;

    public function isFailure(): bool;

    public function isFinished(): bool;

}