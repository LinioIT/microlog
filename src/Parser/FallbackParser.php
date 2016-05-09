<?php

declare(strict_types=1);

namespace Linio\Component\Microlog\Parser;

class FallbackParser implements Parser
{
    const MESSAGE_WAS_EMPTY = 'Message was empty.';

    const MESSAGE_OBJECT_NOT_IMPLEMENTED_TO_STRING = 'Could not parse message of type [%s] for logging.';

    /**
     * @param mixed $message
     * @param array $context
     *
     * @return ParsedMessage
     */
    public function parse($message, array $context = []): ParsedMessage
    {
        if ($message === null) {
            return new ParsedMessage(self::MESSAGE_WAS_EMPTY, $context);
        }

        if (is_array($message)) {
            return new ParsedMessage(print_r($message, true), $context);
        }

        if (is_object($message) && !method_exists($message, '__toString')) {
            return new ParsedMessage(
                sprintf(self::MESSAGE_OBJECT_NOT_IMPLEMENTED_TO_STRING, get_class($message)),
                $context
            );
        }

        return new ParsedMessage((string) $message, $context);
    }

    /**
     * @param mixed $message
     *
     * @return bool
     */
    public function supportsMessage($message): bool
    {
        return true;
    }
}
