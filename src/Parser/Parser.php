<?php
declare(strict_types=1);

namespace Linio\Component\Microlog\Parser;

interface Parser
{
    /**
     * @param mixed $message
     * @param array $context
     *
     * @return ParsedMessage
     */
    public function parse($message, array $context = []): ParsedMessage;

    /**
     * @param mixed $message
     *
     * @return bool
     */
    public function supportsMessage($message): bool;
}
