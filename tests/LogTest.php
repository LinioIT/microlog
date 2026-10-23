<?php

declare(strict_types=1);

namespace Linio\Component\Microlog;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

#[CoversClass(Log::class)]
class LogTest extends TestCase
{
    protected function tearDown(): void
    {
        $reflection = new \ReflectionProperty(Log::class, 'loggers');
        $reflection->setValue(null, [Log::DEFAULT_CHANNEL => new NullLogger()]);
    }

    #[DataProvider('logLevelProvider')]
    public function testItLogsMessages(string $level): void
    {
        $message = 'this is a test';
        $context = ['test' => 'test context'];

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('log')->with($level, $message, $context);

        Log::setLoggerForChannel($logger, Log::DEFAULT_CHANNEL);

        Log::{$level}($message, $context);
    }

    #[DataProvider('logLevelProvider')]
    public function testItLogsMessagesInANonDefaultChannel(string $level): void
    {
        $message = 'this is a test';
        $channel = 'testChannel';
        $context = ['test' => 'test context'];

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('log')->with($level, $message, $context);

        Log::setLoggerForChannel($logger, $channel);

        Log::{$level}($message, $context, $channel);
    }

    public function testItLogsWithAnArbitraryLevel(): void
    {
        $message = 'this is a test';
        $level = 'emergency';

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('log')->with($level, $message, []);

        Log::setLoggerForChannel($logger, Log::DEFAULT_CHANNEL);

        Log::log($level, $message);
    }

    public static function logLevelProvider(): array
    {
        return [
            ['debug'],
            ['info'],
            ['notice'],
            ['warning'],
            ['error'],
            ['critical'],
            ['alert'],
            ['emergency'],
        ];
    }
}
