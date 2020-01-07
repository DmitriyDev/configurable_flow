<?php

namespace Workflow;


use Workflow\Interfaces\StatusInterface;

class Status implements StatusInterface
{
    public const CREATED = "Created";
    public const IN_PROGRESS = "In progress";
    public const SKIPPED = "Skipped";
    public const FINISH_FAILURE = "Failure";
    public const FINISH_SUCCESS = "Success";

    public const DEFAULT = "Unsupported";

    /** @var int */
    protected $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function success(): StatusInterface
    {
        return new self(self::FINISH_SUCCESS);
    }

    public static function failure(): StatusInterface
    {
        return new self(self::FINISH_FAILURE);
    }

    public static function created(): StatusInterface
    {
        return new self(self::CREATED);
    }

    public static function inProgress(): StatusInterface
    {
        return new self(self::IN_PROGRESS);
    }

    public static function skipped(): StatusInterface
    {
        return new self(self::SKIPPED);
    }

    public function isSuccess(): bool
    {
        return $this->status === self::FINISH_SUCCESS;
    }

    public function isFailure(): bool
    {
        return $this->status === self::FINISH_FAILURE;
    }

    public function isFinished(): bool
    {
        return $this->isSuccess() || $this->isFailure();
    }

    function __toString(): string
    {
        switch ($this->status) {
            case self::CREATED:
            case self::IN_PROGRESS:
            case self::FINISH_FAILURE:
            case self::FINISH_SUCCESS:
            case self::SKIPPED:
                return $this->status;
            default:
                return self::DEFAULT;
        }
    }
}