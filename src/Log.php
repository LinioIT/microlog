<?php
declare(strict_types=1);

namespace Linio\Component\Microlog;

use Linio\Component\Microlog\Parser\FallbackParser;
use Linio\Component\Microlog\Parser\ParsedMessage;
use Linio\Component\Microlog\Parser\Parser;
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
     * @var Parser[]
     */
    private static $parsers = [];

    /**
     * @var array
     */
    private static $globalContexts = [];

    /**
     * @var FallbackParser
     */
    private static $fallbackParser;

    /**
     * @param LoggerInterface $logger
     * @param string $channel
     */
    public static function setLoggerForChannel(LoggerInterface $logger, string $channel = self::DEFAULT_CHANNEL)
    {
        self::$loggers[$channel] = $logger;
    }

    /**
     * @param Parser $parser
     */
    public static function addParser(Parser $parser)
    {
        self::$parsers[] = $parser;
    }

    /**
     * Adds a context to all log methods
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
     *
     * @return null
     */
    public static function emergency($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->emergency(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
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
     *
     * @return null
     */
    public static function alert($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->alert(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     *
     * @return null
     */
    public static function critical($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->critical(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     *
     * @return null
     */
    public static function error($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->error(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
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
     *
     * @return null
     */
    public static function warning($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->warning(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
    }

    /**
     * Normal but significant events.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     *
     * @return null
     */
    public static function notice($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->notice(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     *
     * @return null
     */
    public static function info($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->info(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
    }

    /**
     * Detailed debug information.
     *
     * @param mixed $message
     * @param array $context
     * @param string $channel
     *
     * @return null
     */
    public static function debug($message, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->debug(
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $message
     * @param mixed $level
     * @param array $context
     * @param string $channel
     *
     * @return null
     */
    public static function log($message, $level, array $context = [], string $channel = self::DEFAULT_CHANNEL)
    {
        $parsedMessage = self::parseMessage($message, $context);

        self::getLoggerForChannel($channel)->log(
            $level,
            $parsedMessage->getMessage(),
            self::mergeGlobalContexts($parsedMessage->getContext())
        );
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

    /**
     * @param mixed $message
     * @param array $context
     *
     * @return ParsedMessage
     */
    private static function parseMessage($message, array $context): ParsedMessage
    {
        /** @var Parser $parser */
        foreach (self::$parsers as $parser) {
            if (!$parser->supportsMessage($message)) {
                continue;
            }

            return $parser->parse($message, $context);
        }

        return self::getFallbackParser()->parse($message, $context);
    }

    /**
     * @param array $contexts
     *
     * @return array
     */
    private static function mergeGlobalContexts(array $contexts): array
    {
        return array_merge($contexts, self::$globalContexts);
    }

    /**
     * @return FallbackParser
     */
    private static function getFallbackParser(): FallbackParser
    {
        if (!self::$fallbackParser) {
            self::$fallbackParser = new FallbackParser();
        }

        return self::$fallbackParser;
    }
}
