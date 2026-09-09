<?php

declare(strict_types=1);

namespace RedislabsModulesTest;

use Redislabs\Exceptions\InvalidCommandException;
use Redislabs\Test\Command;
use Redislabs\Test\Module;
use Redis;
use Predis;

class ModuleTest extends \Codeception\Test\Unit
{
    /**
     * @var \RedislabsModulesTest\UnitTester
     */
    protected $tester;

    #[\PHPUnit\Framework\Attributes\Test]
    public function shouldRunCommandOriginalRedisClientsCommandSuccessfully(): void
    {
        $redisClient = new Redis();
        $redisClient->connect('127.0.0.1', (int) (getenv('REDIS_PORT') ?: 6379));
        /**
         * @var Module
         */
        $module = Module::createWithPhpRedis($redisClient);
        $command = new Command('BAZ', 'QUZ');
        $module->runCommand($command);
        $this->assertEquals('QUZ', $redisClient->get('BAZ'));
        $redisClient->flushAll();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shouldRunCommandOriginalPredisClientsCommandSuccessfully(): void
    {
        $redisClient = new Predis\Client(['port' => (int) (getenv('REDIS_PORT') ?: 6379)]);
        /**
         * @var Module
         */
        $module = Module::createWithPredis($redisClient);
        $command = new Command('BAZ', 'QUZ');
        $module->runCommand($command);
        $this->assertEquals('QUZ', $redisClient->get('BAZ'));
        $redisClient->flushall();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shouldFailForMagicMethodCall(): void
    {
        $redisClient = new Predis\Client(['port' => (int) (getenv('REDIS_PORT') ?: 6379)]);
        /**
         * @var Module
         */
        $module = Module::createWithPredis($redisClient);
        $this->expectException(InvalidCommandException::class);
        $module->invalidMethod('test');
    }
}
