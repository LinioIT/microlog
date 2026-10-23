<?php

declare(strict_types=1);

namespace Linio\Component\Microlog;

class ThrowableProcessor
{
    /**
     * @param array{message: mixed, context: array<string, mixed>} $record
     *
     * @return array{message: mixed, context: array<string, mixed>}
     */
    public function __invoke(array $record): array
    {
        if (!$record['message'] instanceof \Throwable) {
            return $record;
        }

        /** @var \Throwable $throwable */
        $throwable = $record['message'];

        $record['message'] = $throwable->getMessage();
        $record['context']['exception'] = $throwable;

        return $record;
    }
}
