<?php

declare(strict_types=1);

namespace Redislabs\RedisClient;

use Redislabs\Interfaces\RedisClientInterface;
use Redis as RedisClient;
use RedisCluster;

final class Redis implements RedisClientInterface
{
    public function __construct(private readonly RedisClient|RedisCluster $redisClient)
    {
    }

    public function getClient(): RedisClient|RedisCluster
    {
        return $this->redisClient;
    }

    public function rawCommand(string $command, array $arguments): mixed
    {
        if ($this->redisClient instanceof RedisCluster) {
            // Cluster rawCommand needs a routing key in addition to the wire arguments.
            $keyPosition = strtoupper($command) === 'JSON.DEBUG' ? 1 : 0;
            $routingKey = $arguments[$keyPosition] ?? '';
            return $this->redisClient->rawCommand($routingKey, $command, ...$arguments);
        }
        return $this->redisClient->rawCommand($command, ...$arguments);
    }
}
