<?php
declare(strict_types=1);

namespace Linio\Component\Microlog\Parser;

class FallbackParser implements Parser
{
    /**
     * @param mixed $message
     * @param array $context
     *
     * @return ParsedMessage
     */
    public function parse($message, array $context = []): ParsedMessage
    {
        if (is_object($message) && !method_exists($message, '__toString')) {
            return new ParsedMessage(
                sprintf('Could parse message of type [%s] for logging.', get_class($message)),
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
