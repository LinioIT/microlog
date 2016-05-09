<?php
declare(strict_types=1);

namespace Linio\Component\Microlog;

use Exception;
use Linio\Component\Microlog\Parser\ThrowableParser;
use PHPUnit_Framework_TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionProperty;
use stdClass;

class LogTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        $reflection = new ReflectionProperty(Log::class, 'loggers');
        $reflection->setAccessible(true);
        $reflection->setValue(null, [Log::DEFAULT_CHANNEL => new NullLogger()]);

        $reflection = new ReflectionProperty(Log::class, 'parsers');
        $reflection->setAccessible(true);
        $reflection->setValue(null, []);

        $reflection = new ReflectionProperty(Log::class, 'globalContexts');
        $reflection->setAccessible(true);
        $reflection->setValue(null, []);
    }

    /**
     * @dataProvider logMethodProvider
     *
     * @param string $method
     */
    public function testItLogsWithAStringMessageWithNoContextInTheDefaultChannel(string $method)
    {
        $message = 'this is a test';

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->{$method}($message, [])->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal());

        Log::{$method}($message);
    }

    /**
     * @dataProvider logMethodProvider
     *
     * @param string $method
     */
    public function testItLogsWithAStringMessageWithAContextInTheDefaultChannel(string $method)
    {
        $message = 'this is a test';
        $context = ['test' => 'test context'];

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->{$method}($message, $context)->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal());

        Log::{$method}($message, $context);
    }

    /**
     * @dataProvider logMethodProvider
     *
     * @param string $method
     */
    public function testItLogsWithAStringMessageWithAContextInANonDefaultChannel(string $method)
    {
        $message = 'this is a test';
        $channel = 'testChannel';
        $context = ['test' => 'test context'];

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->{$method}($message, $context)->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal(), $channel);

        Log::{$method}($message, $context, $channel);
    }

    /**
     * @dataProvider logMethodProvider
     *
     * @param string $method
     */
    public function testItLogsWithAnExceptionMessage(string $method)
    {
        $message = new Exception('test');

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->{$method}($message->getMessage(), ['exception' => $message])->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal());
        Log::addParser(new ThrowableParser());

        Log::{$method}($message);
    }

    /**
     * @dataProvider logMethodProvider
     *
     * @param string $method
     */
    public function testItLogsWithAGlobalContext(string $method)
    {
        $message = 'this is a test';

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->{$method}($message, ['test' => 'testing'])->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal());
        Log::addGlobalContext('test', 'testing');

        Log::{$method}($message);
    }

    public function testItLogsWithAnArbitraryLevel()
    {
        $message = 'this is a test';
        $level = 'someLevel';

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->log($level, $message, [])->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal());

        Log::log($message, $level);
    }

    /**
     * @dataProvider logMethodProvider
     *
     * @param string $method
     */
    public function testItLogsWithAnObjectMessageWithNoEquivalentParser(string $method)
    {
        $message = new stdClass();
        $expectedMessage = 'Could parse message of type [stdClass] for logging.';

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->{$method}($expectedMessage, [])->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal());

        Log::{$method}($message);
    }

    /**
     * @dataProvider logMethodProvider
     *
     * @param string $method
     */
    public function testItLogsWithAThrowableMessage(string $method)
    {
        $exception = new Exception();

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->{$method}($exception->getMessage(), ['exception' => $exception])->shouldBeCalled();

        Log::setLoggerForChannel($logger->reveal());
        Log::addParser(new ThrowableParser());

        Log::{$method}($exception);
    }

    public function logMethodProvider(): array
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
