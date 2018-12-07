<?php
declare(strict_types=1);

namespace Redislabs\Interfaces;

interface ModuleInterface
{
    public function runCommand(CommandInterface $command);
}
