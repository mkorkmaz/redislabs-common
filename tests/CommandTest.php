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

    /**
     * @test
     */
    public function shouldGetReturnCommandAndArgumentsSuccessfully(): void
    {
        $command = new Command('param1', 'param2');
        $this->assertEquals('SET', $command->getCommand());
        $this->assertEquals(['param1', 'param2'], $command->getArguments());
    }
}
