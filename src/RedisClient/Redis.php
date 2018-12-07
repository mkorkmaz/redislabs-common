<?php
declare(strict_types=1);

namespace Redislabs\RedisClient;

use Redislabs\Interfaces\RedisClientInterface;
use Redis as RedisClient;

final class Redis implements RedisClientInterface
{
    private $redisClient;

    public function __construct(RedisClient $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public function getClient() : RedisClient
    {
        return $this->redisClient;
    }

    public function rawCommand(string $command, array $arguments)
    {
        return $this->redisClient->rawCommand($command, ...$arguments);
    }
}
