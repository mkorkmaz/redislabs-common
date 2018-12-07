<?php
declare(strict_types=1);

namespace Redislabs\Test;

use Redislabs\Interfaces\RedisClientInterface;
use Redislabs\RedisClient\Predis as RedislabsPredisClient;
use Redislabs\RedisClient\Redis as RedislabsPhpRedisClient;
use Predis\Client as PredisClient;
use Redis as PhpRedisClient;

final class Client
{
    private $redisClient;

    private function __construct(RedisClientInterface $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    public static function createWithPredis(PredisClient $predisClient) : self
    {
        return new self(new RedislabsPredisClient($predisClient));
    }

    public static function createWithPhpRedis(PhpRedisClient $predisClient) : self
    {
        return new self(new RedislabsPhpRedisClient($predisClient));
    }
    public function raw($command, ...$arguments)
    {
        return $this->redisClient->rawCommand($command, $arguments);
    }
    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * Using this magic method enables this library to extend redis clients
     * to use redislabs modules
     */
    public function __call(string $name, array $arguments)
    {
        $redisClient = $this->redisClient->getClient();
        return call_user_func_array([$redisClient, $name], $arguments);
    }
}
