<?php
declare(strict_types=1);

namespace Redislabs\Module;

use Redislabs\Exceptions\InvalidCommandException;
use Redislabs\Interfaces\CommandInterface;
use Redislabs\Interfaces\RedisClientInterface;
use Predis\Client as PredisClient;
use Redis as PhpRedisClient;
use Redislabs\RedisClient\Predis as RedislabsPredisClient;
use Redislabs\RedisClient\Redis as RedislabsPhpRedisClient;

trait ModuleTrait
{
    protected $redisClient;

    public function __construct(RedisClientInterface $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    final public static function createWithPredis(PredisClient $predisClient) : self
    {
        return new static(
            new RedislabsPredisClient($predisClient)
        );
    }

    final public static function createWithPhpRedis(PhpRedisClient $predisClient) : self
    {
        return new static(
            new RedislabsPhpRedisClient($predisClient)
        );
    }

    final public function runCommand(CommandInterface $command)
    {
        $response = $this->redisClient->rawCommand(
            $command->getCommand(),
            $command->getArguments()
        );
        $callback = $command->getResponseCallback();
        return $callback ? $callback($response) : $response;
    }

    final public function __call($name, $arguments)
    {
        throw new InvalidCommandException(
            sprintf('%s::%s is not a valid method', static::$moduleName, $name)
        );
    }
}
