<?php
declare(strict_types=1);

namespace Linio\Component\Microlog\Parser;

use Throwable;

class ThrowableParser implements Parser
{
    /**
     * @param mixed $message
     * @param array $context
     *
     * @return ParsedMessage
     */
    public function parse($message, array $context = []): ParsedMessage
    {
        $context['exception'] = $message;

        return new ParsedMessage($message->getMessage(), $context);
    }

    /**
     * @param mixed $message
     *
     * @return bool
     */
    public function supportsMessage($message): bool
    {
        return $message instanceof Throwable;
    }
}
