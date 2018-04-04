<?php

declare(strict_types=1);

namespace Linio\Component\Microlog;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

class Log
{
    const DEFAULT_CHANNEL = 'default';

    /**
     * @var LoggerInterface[]
     */
    private static $loggers = [];

    public static function setLoggerForChannel(LoggerInterface $logger, string $channel)
    {
        self::$loggers[$channel] = $logger;
    }

    /**
     * System is unusable.
     */
    public static function emergency($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::EMERGENCY, $message, $context, $channel);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     */
    public static function alert($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::ALERT, $message, $context, $channel);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     */
    public static function critical($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::CRITICAL, $message, $context, $channel);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     */
    public static function error($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::ERROR, $message, $context, $channel);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     */
    public static function warning($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::WARNING, $message, $context, $channel);
    }

    /**
     * Normal but significant events.
     */
    public static function notice($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::NOTICE, $message, $context, $channel);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     */
    public static function info($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::INFO, $message, $context, $channel);
    }

    /**
     * Detailed debug information.
     */
    public static function debug($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::log(LogLevel::DEBUG, $message, $context, $channel);
    }

    /**
     * Logs with an arbitrary level.
     */
    public static function log($level, $message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        self::getLoggerForChannel($channel)->log($level, $message, $context);
    }

    private static function getLoggerForChannel(string $channel): LoggerInterface
    {
        if (!isset(self::$loggers[$channel])) {
            self::$loggers[$channel] = new NullLogger();
        }

        return self::$loggers[$channel];
    }
}
