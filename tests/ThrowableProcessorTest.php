<?php

declare(strict_types=1);

namespace Linio\Component\Microlog;

use Exception;
use PHPUnit\Framework\TestCase;
use Throwable;

class ThrowableProcessorTest extends TestCase
{
    public function testItDoesNotProcessMessagesThatAreNotThrowables()
    {
        $record = [
            'message' => 'notAThrowable',
            'context' => [],
        ];

        $processor = new ThrowableProcessor();
        $actual = $processor($record);

        $this->assertSame($record, $actual);
    }

    public function testItProcessesMessagesThatAreThrowables()
    {
        $record = [
            'message' => new Exception('Test Message'),
            'context' => [],
        ];

        $processor = new ThrowableProcessor();
        $actual = $processor($record);

        $this->assertSame('Test Message', $actual['message']);
        $this->assertInstanceOf(Throwable::class, $actual['context']['exception']);
    }
}
