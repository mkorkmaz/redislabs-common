<?php
declare(strict_types=1);

namespace Redislabs\Test;

use Redislabs\Command\CommandAbstract;
use Redislabs\Interfaces\CommandInterface;

class Command extends CommandAbstract implements CommandInterface
{
    protected static $command = 'SET';

    public function __construct()
    {
        $this->arguments = func_get_args();
    }
}
