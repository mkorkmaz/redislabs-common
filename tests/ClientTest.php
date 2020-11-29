<?php

declare(strict_types=1);

namespace RedislabsModulesTest;

use Redislabs\Test\Client;
use Redis;
use Predis;

class ClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \RedislabsModulesTest\UnitTester
     */
    protected $tester;

    /**
     * @test
     */
    public function shouldRunCommandOriginalRedisClientsCommandSuccessfully(): void
    {
        $redisClient = new Redis();
        $redisClient->connect('127.0.0.1');

        /**
         * @var Client
         */
        $redisLabsClient = Client::createWithPhpRedis($redisClient);
        $redisLabsClient->set('foo', 1);
        $this->assertEquals(1, $redisLabsClient->get('foo'), 'Ext-redis client runs its native functions');
        $redisLabsClient->del('foo');
        $redisLabsClient->raw('SET', 'FOO', 'BAR');
        $fooValue = $redisLabsClient->raw('GET', 'FOO');
        $this->assertEquals('BAR', $fooValue);
    }

    /**
     * @test
     */
    public function shouldRunCommandOriginalPredisClientsCommandSuccessfully(): void
    {
        /**
         * @var Client
         */
        $redisLabsClient = Client::createWithPredis(new Predis\Client());
        $redisLabsClient->set('foo', 'bar');
        $this->assertEquals('bar', $redisLabsClient->get('foo'), 'Predis client runs its native functions');
        $redisLabsClient->del('foo');
        $redisLabsClient->raw('SET', 'FOO', 'BAR');
        $fooValue = $redisLabsClient->raw('GET', 'FOO');
        $this->assertEquals('BAR', $fooValue);
    }
}
