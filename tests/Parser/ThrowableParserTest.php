<?php
declare(strict_types=1);

namespace Parser;

use Exception;
use Linio\Component\Microlog\Parser\ThrowableParser;
use PHPUnit_Framework_TestCase;

class ThrowableParserTest extends PHPUnit_Framework_TestCase
{
    public function testItSupportsThrowables()
    {
        $throwable = new Exception();

        $parser = new ThrowableParser();

        $this->assertTrue($parser->supportsMessage($throwable));
    }

    public function testItParsesAThrowable()
    {
        $exception = new Exception();

        $parser = new ThrowableParser();

        $parsedMessage = $parser->parse($exception);

        $this->assertSame($parsedMessage->getContext()['exception'], $exception);
        $this->assertSame($parsedMessage->getMessage(), $exception->getMessage());
    }
}
