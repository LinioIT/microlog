# Linio Microlog
Microlog - Static wrapper around Monolog

## Purpose
Logging is a part of all applications. Having to inject in a logging service requires a dependency that has nothing to do with the code that you are writing. Microlog was created to remove this requirement.

Microlog allows you create log entries in different contexts from any part of your application. Microlog is built with never throwing exceptions in mind. An exception caused by logging causes the normal flow of your application to fail, and logging shouldn't do that.

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
Log::setLoggerForChannel($defaultLogger, Log::DEFAULT_CHANNEL);

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

## Multiple Channels
Channels allow you to configure different handlers for different types of messages.

By default, all logs will be created in the default logging channel `Log::DEFAULT_CHANNEL` (`default`). If you wish to specify a different channel when logging, the second parameter to all non-arbitrary level log methods.

```php
<?php

declare(strict_types=1);

use Linio\Component\Microlog\Log;
use Monolog\Handler\StreamHandler;

$defaultLogger = new NullLogger();
Log::setLoggerForChannel($defaultLogger, 'emergency');

Log::emergency('This is an emergency in the emergency channel', 'emergency');
```

## Contributing
Feel free to send your contributions as a PR. Make sure you update/write new tests to support your contribution.
Please follow PSR-2.
