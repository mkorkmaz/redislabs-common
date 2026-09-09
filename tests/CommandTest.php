<?php

declare(strict_types=1);

namespace RedislabsModulesTest;

use Redislabs\Test\Command;

class CommandTest extends \Codeception\Test\Unit
{
    /**
     * @var \RedislabsModulesTest\UnitTester
     */
    protected $tester;

    public function testJsonDecodePreservesValuesAndDecodesObjectsAsArrays(): void
    {
        foreach ([null, 'null', 'false', '0', '[]', '{"value":1}'] as $index => $json) {
            $expected = [null, null, false, 0, [], ['value' => 1]];
            self::assertSame($expected[$index], Command::jsonDecode($json));
        }
    }

    public function testJsonDecodeRejectsMalformedJson(): void
    {
        $this->expectException(\JsonException::class);
        Command::jsonDecode('{');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function shouldGetReturnCommandAndArgumentsSuccessfully(): void
    {
        $command = new Command('param1', 'param2');
        $this->assertEquals('SET', $command->getCommand());
        $this->assertEquals(['param1', 'param2'], $command->getArguments());
    }
}
