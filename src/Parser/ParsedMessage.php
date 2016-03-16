<?php
declare(strict_types=1);

namespace Linio\Component\Microlog\Parser;

class ParsedMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $context = [];

    /**
     * @param string $message
     * @param array $context
     */
    public function __construct(string $message, array $context)
    {
        $this->message = $message;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
