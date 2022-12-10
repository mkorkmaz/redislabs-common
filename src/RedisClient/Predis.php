<?php

declare(strict_types=1);

namespace Redislabs\RedisClient;

use Redislabs\Interfaces\RedisClientInterface;
use Predis\ClientInterface as RedisClient;

final class Predis implements RedisClientInterface
{
    public function __construct(private RedisClient $redisClient)
    {

    }

    public function getClient(): RedisClient
    {
        return $this->redisClient;
    }

    public function rawCommand(string $command, array $arguments)
    {
        array_unshift($arguments, $command);
        return $this->redisClient->executeRaw($arguments);
    }
}
