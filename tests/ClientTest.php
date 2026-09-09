<?php

declare(strict_types=1);

namespace RedislabsModulesTest;

use Redislabs\Test\Client;
use Redis;
use Predis;

class ClientTest extends \Codeception\Test\Unit
{
    protected $tester;

    #[\PHPUnit\Framework\Attributes\Test]
    public function shouldRunCommandOriginalRedisClientsCommandSuccessfully(): void
    {
        $redisClient = new Redis();
        $redisClient->connect('127.0.0.1', (int) (getenv('REDIS_PORT') ?: 6379));

        /**
         * @var Client
         */
        $redisLabsClient = Client::createWithPhpRedis($redisClient);
        $redisClient = $redisLabsClient->getClient();
        $this->assertSame(Redis::class, $redisClient::class);
        $redisLabsClient->set('foo', 1);
        $this->assertEquals(1, $redisLabsClient->get('foo'), 'Ext-redis client runs its native functions');
        $redisLabsClient->del('foo');
        $redisLabsClient->raw('SET', 'FOO', 'BAR');
        $fooValue = $redisLabsClient->raw('GET', 'FOO');
        $this->assertEquals('BAR', $fooValue);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shouldRunCommandOriginalPredisClientsCommandSuccessfully(): void
    {
        /**
         * @var Client
         */
        $redisClient = new Predis\Client(['port' => (int) (getenv('REDIS_PORT') ?: 6379)]);
        $redisLabsClient = Client::createWithPredis($redisClient);
          $redisLabsClient->raw('set', 'foo', 'bar');
        $this->assertEquals('bar', $redisLabsClient->raw('get', 'foo'), 'Predis client runs its native functions');
        $redisLabsClient->raw('get', 'foo');
        $redisLabsClient->raw('SET', 'FOO', 'BAR');
        $fooValue = $redisLabsClient->raw('GET', 'FOO');
        $this->assertEquals('BAR', $fooValue);
    }
}
