<?php
declare(strict_types=1);

namespace Redislabs\Test;

use Redislabs\Interfaces\ModuleInterface;
use Redislabs\Module\ModuleTrait;

class Module implements ModuleInterface
{
    use ModuleTrait;
    protected static $moduleName = 'Module';
}
