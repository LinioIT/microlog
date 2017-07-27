<?php

declare(strict_types=1);

namespace Parser;

use Exception;
use Linio\Component\Microlog\Parser\FallbackParser;
use PHPUnit\Framework\TestCase;
use stdClass;

class FallbackParserTest extends TestCase
{
    public function testItSupportsStrings()
    {
        $parser = new FallbackParser();

        $this->assertTrue($parser->supportsMessage('test'));
    }

    public function testItSupportsInts()
    {
        $parser = new FallbackParser();

        $this->assertTrue($parser->supportsMessage(100));
    }

    public function testItSupportsFloats()
    {
        $parser = new FallbackParser();

        $this->assertTrue($parser->supportsMessage(1.0));
    }

    public function testItSupportsBools()
    {
        $parser = new FallbackParser();

        $this->assertTrue($parser->supportsMessage(true));
    }

    public function testItSupportsArrays()
    {
        $parser = new FallbackParser();

        $this->assertTrue($parser->supportsMessage(['test' => 'test']));
    }

    public function testItSupportsThrowables()
    {
        $parser = new FallbackParser();

        $this->assertTrue($parser->supportsMessage(new Exception()));
    }

    public function testItSupportsNulls()
    {
        $parser = new FallbackParser();

        $this->assertTrue($parser->supportsMessage(null));
    }

    public function testItParsesStrings()
    {
        $message = 'test';

        $parser = new FallbackParser();

        $this->assertSame($message, $parser->parse($message)->getMessage());
    }

    public function testItParsesInts()
    {
        $message = 1;

        $parser = new FallbackParser();

        $this->assertSame((string) $message, $parser->parse($message)->getMessage());
    }

    public function testItParsesFloats()
    {
        $message = 1.0;

        $parser = new FallbackParser();

        $this->assertSame((string) $message, $parser->parse($message)->getMessage());
    }

    public function testItParsesBools()
    {
        $message = true;

        $parser = new FallbackParser();

        $this->assertSame((string) $message, $parser->parse($message)->getMessage());
    }

    public function testItParsesArrays()
    {
        $message = ['test' => 'test'];

        $parser = new FallbackParser();

        $this->assertSame(print_r($message, true), $parser->parse($message)->getMessage());
    }

    public function testItParsesAThrowable()
    {
        $exception = new Exception();

        $parser = new FallbackParser();

        $this->assertStringStartsWith('Exception in', $parser->parse($exception)->getMessage());
    }

    public function testItParsesNulls()
    {
        $message = null;

        $parser = new FallbackParser();

        $this->assertSame('Message was empty.', $parser->parse($message)->getMessage());
    }

    public function testItUsesAFallbackMessageWithAnObjectThatDoesNotImplementToString()
    {
        $message = new stdClass();

        $parser = new FallbackParser();

        $this->assertSame('Could not parse message of type [stdClass] for logging.', $parser->parse($message)->getMessage());
    }
}
