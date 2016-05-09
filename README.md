# Linio Microlog
Microlog - Static wrapper around PSR-3 loggers

## Purpose
Logging is a part of all applications. Having to inject in a logging service requires a dependency that has nothing to do with the code that you are writing. Microlog was created to remove this requirement.

Microlog allows you create log entries in different contexts from any part of your application. Microlog is built with never throwing exceptions in mind. An exception caused by logging causes the normal flow of your application to fail, and logging shouldn't do that.

While seldaek/monolog is recommend, any PSR-3 compatible library will work.

## Installation

To install microlog:

```
$ composer require linio/microlog
```

## Basic Usage
```php
<?php

declare(strict_types=1);

use Linio\Component\Microlog\Log;
use Monolog\Handler\StreamHandler;

// Register a logger for a channel
$defaultLogger = new StreamHandler();
Log::setLoggerForChannel(Log::DEFAULT_CHANNEL, $defaultLogger);

// Log an emergency
Log::emergency('This is a test emergency');

// Log an alert
Log::alert('This is a test alert');

// Log a critical error
Log::critical('This is a test critical error');

// Log an error
Log::error('This is a test error');

// Log a warning
Log::warning('This is a test warning');

// Log a notice
Log::notice('This is a test notice');

// Log a bit of information
Log::info('This is test information');

// Log debug information
Log::debug('This is test debug information');

// Log at an arbitrary level
Log::log('emergency', 'This is a test entry at an arbitrary level of emergency');
```

## Advanced Usage
Microlog supports several cool features such as global contexts, multiple channels and parsers.

### Parsers
A parser is a class that can modify the message and context for a given entry. One such parser is included by default. It parses `\Throwable`s to automatically extract the message and add the throwable in the context. Monolog has built in exception handling when an exception is the value of the `exception` key of the context.

One of the benefits to these parsers is that you can send anything as the message, not just strings and classes that implement `__toString`. Each parser is executed in the order it is added until one returns true in the `supports($message)` method. If it returns true, it is asked to parse the message.

By default, microlog will use a fallback parser to make sure that a message is always logged no matter what is passed in as the message.

### Multiple Channels
Channels allow you to configure different handlers for different types of messages.

By default, all logs will be created in the default logging channel `Log::DEFAULT_CHANNEL` (`default`). If you wish to specify a different channel when logging, the second parameter to all non-arbitrary level log methods.

```php
<?php

declare(strict_types=1);

use Linio\Component\Microlog\Log;
use Monolog\Handler\StreamHandler;

$defaultLogger = new StreamHandler();
Log::setLoggerForChannel(Log::DEFAULT_CHANNEL, $defaultLogger);

Log::emergency('This is an emergency in the emergency channel', 'emergency');
```

### Global Contexts
Global contexts allow you to add certain bits of information to all log entries. This is extremely useful for logging things such as a `requestId`, `customerId`, etc.

```php
<?php

declare(strict_types=1);

use Linio\Component\Microlog\Log;
use Monolog\Handler\StreamHandler;

$defaultLogger = new StreamHandler();
Log::setLoggerForChannel(Log::DEFAULT_CHANNEL, $defaultLogger);

Log::addGlobalContext('requestId', uniqid());
```

## Contributing
Feel free to send your contributions as a PR. Make sure you update/write new tests to support your contribution.
Please follow PSR-2.
