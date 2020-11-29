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

    final public function getResponseCallback(): ?callable
    {
        return $this->responseCallback;
    }

    final public static function jsonDecode(?string $jsonData)
    {
        if ($jsonData === null) {
            return null;
        }
        return json_decode($jsonData, JSON_OBJECT_AS_ARRAY, 512, JSON_THROW_ON_ERROR);
    }
}
