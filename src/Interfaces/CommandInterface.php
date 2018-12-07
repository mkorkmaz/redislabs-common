<?php
declare(strict_types=1);

namespace Redislabs\Interfaces;

interface CommandInterface
{
    public function getCommand() : string;
    public function getArguments() : array;
    public function getResponseCallback() : ?callable;
}
