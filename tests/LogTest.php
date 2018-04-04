<?php

declare(strict_types=1);

namespace Linio\Component\Microlog;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionProperty;

class LogTest extends TestCase
{
    protected function tearDown()
    {
        $reflection = new ReflectionProperty(Log::class, 'loggers');
        $reflection->setAccessible(true);
        $reflection->setValue(null, [Log::DEFAULT_CHANNEL => new NullLogger()]);
    }

    /**
     * @dataProvider logLevelProvider
     */
    public function testItLogsMessages(string $level)
    {
        $message = 'this is a test';
        $context = ['test' => 'test context'];

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log($level, $message, $context)->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal(), Log::DEFAULT_CHANNEL);

        Log::{$level}($message, $context);
    }

    /**
     * @dataProvider logLevelProvider
     */
    public function testItLogsMessagesInANonDefaultChannel(string $level)
    {
        $message = 'this is a test';
        $channel = 'testChannel';
        $context = ['test' => 'test context'];

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log($level, $message, $context)->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal(), $channel);

        Log::{$level}($message, $context, $channel);
    }

    public function testItLogsWithAnArbitraryLevel()
    {
        $message = 'this is a test';
        $level = 'emergency';

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log($level, $message, [])->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal(), Log::DEFAULT_CHANNEL);

        Log::log($level, $message);
    }

    public function logLevelProvider(): array
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
