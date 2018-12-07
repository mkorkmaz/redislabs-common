<?php
declare(strict_types=1);

namespace Redislabs\Interfaces;

interface RedisClientInterface
{
    public function getClient();
    public function rawCommand(string $command, array $arguments);
}
