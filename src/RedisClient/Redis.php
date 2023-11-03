<?php

declare(strict_types=1);

namespace Redislabs\RedisClient;

use Redislabs\Interfaces\RedisClientInterface;
use Redis as RedisClient;
use RedisCluster;

final class Redis implements RedisClientInterface
{
    public function __construct(private RedisClient|RedisCluster $redisClient)
    {
    }

    public function getClient(): RedisClient|RedisCluster
    {
        return $this->redisClient;
    }

    public function rawCommand(string $command, array $arguments)
    {
        return $this->redisClient->rawCommand($command, ...$arguments);
    }
}
