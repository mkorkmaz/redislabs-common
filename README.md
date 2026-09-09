# Common library for Redislabs Modules.

Contains Interfaces, General Exceptions, Abstracts and Traits


[![Build Status](https://api.travis-ci.org/mkorkmaz/redislabs-common.svg?branch=master)](https://travis-ci.org/mkorkmaz/redislabs-common) [![Coverage Status](https://coveralls.io/repos/github/mkorkmaz/redislabs-common/badge.svg?branch=master)](https://coveralls.io/github/mkorkmaz/redislabs-common?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mkorkmaz/redislabs-common/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mkorkmaz/redislabs-common/?branch=master) [![Latest Stable Version](https://poser.pugx.org/mkorkmaz/redislabs-common/v/stable)](https://packagist.org/packages/mkorkmaz/redislabs-common) [![Total Downloads](https://poser.pugx.org/mkorkmaz/redislabs-common/downloads)](https://packagist.org/packages/mkorkmaz/redislabs-common) [![Latest Unstable Version](https://poser.pugx.org/mkorkmaz/redislabs-common/v/unstable)](https://packagist.org/packages/mkorkmaz/redislabs-common) [![License](https://poser.pugx.org/mkorkmaz/redislabs-common/license)](https://packagist.org/packages/mkorkmaz/redislabs-common)


## PhpRedis Cluster

The PhpRedis adapter accepts `Redis` and `RedisCluster`. For cluster commands,
it supplies the first command argument as the routing key without removing it
from the command. `JSON.DEBUG MEMORY` uses the second argument; `JSON.DEBUG HELP`
and commands without arguments use an empty routing key to select one node.
Node commands are not broadcast to all masters.

Raw commands with other key positions (for example, `EVAL`) need the native
`RedisCluster` API with an explicit routing key. Multi-key commands must use keys
in the same hash slot. This change does not add Predis cluster routing.

Run the adapter regression tests without a server:

```sh
vendor/bin/phpunit --bootstrap vendor/autoload.php tests/RedisClusterTest.php
```

Release the cluster routing fix as `2.0.0` before releasing the corresponding
`redislabs-rejson` dependency update.


## PHP 8.5 migration

PHP 8.5 or later in the 8.x series is required (`^8.5`). Development tools and
CI now target PHP 8.5. This is a breaking release; publish it as a new major
version. The `common` package must be released as `2.0.0` before the corresponding
`rejson` release.

Command properties now have native types: `string $command`, `array $arguments`,
and `?Closure $responseCallback`. Custom command subclasses must use compatible
property types. Convert callable arrays or strings with `Closure::fromCallable()`
before assigning a response callback. Module client references are readonly;
create a new module instance to replace its connection.

The refactor uses PHP 8.5 property `#[Override]` checks, first-class callables in
property defaults, and `clone($object, $properties)` for debug command copies.
See the [PHP 8.5 migration guide](https://www.php.net/manual/en/migration85.new-features.php).

Standalone integration tests use `REDIS_PORT` (default `6379`). Run them only
against a disposable Redis instance: the existing standalone suite uses `FLUSHALL`.

```sh
REDIS_PORT=16379 vendor/bin/codecept run unit
```

`createWithPredis()` now requires `Predis\Client` (including subclasses), because
it uses `executeRaw()`, which is not part of `Predis\ClientInterface`. For a custom
transport, implement `Redislabs\Interfaces\RedisClientInterface` and pass that
adapter to the module constructor.
