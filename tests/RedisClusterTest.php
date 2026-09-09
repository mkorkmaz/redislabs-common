<?php

declare(strict_types=1);

namespace RedislabsModulesTest;

use PHPUnit\Framework\TestCase;
use Redis;
use RedisCluster;
use Redislabs\RedisClient\Redis as Adapter;

final class RedisClusterTest extends TestCase
{
    public function testModuleFactoryPreservesClusterClientAndRoutesCommands(): void
    {
        $client = $this->getMockBuilder(RedisCluster::class)
            ->disableOriginalConstructor()->onlyMethods(['rawCommand'])->getMock();
        $client->expects($this->once())->method('rawCommand')
            ->with('document', 'JSON.GET', 'document', '$')->willReturn('[1]');
        $module = \Redislabs\Test\Module::createWithPhpRedisCluster($client);
        self::assertSame($client, $module->getClient());
        self::assertSame('[1]', $module->raw('JSON.GET', 'document', '$'));
    }

    public function testClusterRoutingPreservesCommandArguments(): void
    {
        $cases = [
            ['JSON.SET', ['{doc}:1', '$', '{"value":1}'], '{doc}:1'],
            ['JSON.GET', ['0', '$'], '0'],
            ['JSON.GET', ['', '$'], ''],
            ['JSON.MGET', ['{doc}:1', '{doc}:2', '$'], '{doc}:1'],
            ['JSON.DEBUG', ['MEMORY', '{doc}:1', '$'], '{doc}:1'],
            ['json.debug', ['MEMORY', '{doc}:1', '.'], '{doc}:1'],
            ['JSON.DEBUG', ['HELP'], ''],
            ['PING', [], ''],
        ];
        foreach ($cases as [$command, $arguments, $routingKey]) {
            $client = $this->getMockBuilder(RedisCluster::class)
                ->disableOriginalConstructor()->onlyMethods(['rawCommand'])->getMock();
            $client->expects($this->once())->method('rawCommand')
                ->with($routingKey, $command, ...$arguments)->willReturn('response');
            $adapter = new Adapter($client);
            self::assertSame($client, $adapter->getClient());
            self::assertSame('response', $adapter->rawCommand($command, $arguments));
        }
    }

    public function testStandaloneCommandArgumentsAreUnchanged(): void
    {
        $client = $this->getMockBuilder(Redis::class)->onlyMethods(['rawCommand'])->getMock();
        $client->expects($this->once())->method('rawCommand')
            ->with('JSON.GET', 'document', '$')->willReturn('[1]');
        $adapter = new Adapter($client);
        self::assertSame('[1]', $adapter->rawCommand('JSON.GET', ['document', '$']));
    }
}
