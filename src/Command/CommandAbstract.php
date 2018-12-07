<?php
declare(strict_types=1);

namespace Redislabs\Command;

abstract class CommandAbstract
{
    protected static $command;
    protected $arguments;
    protected $responseCallback;

    final public function getCommand(): string
    {
        return static::$command;
    }

    final public function getArguments(): array
    {
        return $this->arguments;
    }

    final public function getResponseCallback() : ?callable
    {
        return $this->responseCallback;
    }
}
