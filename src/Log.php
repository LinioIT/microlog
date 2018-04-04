<?php

declare(strict_types=1);

namespace Linio\Component\Microlog;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Log
{
    const DEFAULT_CHANNEL = 'default';

    /**
     * @var LoggerInterface[]
     */
    private static $loggers = [];

    /**
     * @var array
     */
    private static $globalContexts = [];

    /**
     * @param LoggerInterface $logger
     * @param string $channel
     */
    public static function setLoggerForChannel(LoggerInterface $logger, string $channel)
    {
        self::$loggers[$channel] = $logger;
    }

    /**
     * Adds a context to all log methods.
     *
     * This is useful for things like a unique id per request.
     *
     * @param string $context
     * @param string $value
     */
    public static function addGlobalContext(string $context, string $value)
    {
        self::$globalContexts[$context] = $value;
    }

    /**
     * System is unusable.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function emergency($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::EMERGENCY, $message, $context, $channel);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function alert($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::ALERT, $message, $context, $channel);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function critical($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::CRITICAL, $message, $context, $channel);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function error($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::ERROR, $message, $context, $channel);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function warning($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::WARNING, $message, $context, $channel);
    }

    /**
     * Normal but significant events.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function notice($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::NOTICE, $message, $context, $channel);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function info($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::INFO, $message, $context, $channel);
    }

    /**
     * Detailed debug information.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     */
    public static function debug($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(Logger::DEBUG, $message, $context, $channel);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $message
     * @param mixed $level
     * @param array $context
     * @param string $channel
     */
    public static function log($message, $level, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::getLoggerForChannel($channel)->log($level, $message, $context);
    }

    /**
     * @param string $channel
     *
     * @return LoggerInterface
     */
    private static function getLoggerForChannel(string $channel): LoggerInterface
    {
        if (!isset(self::$loggers[$channel])) {
            self::$loggers[$channel] = new NullLogger();
        }

        return self::$loggers[$channel];
    }
}
