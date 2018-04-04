<?php

declare(strict_types=1);

namespace Linio\Component\Microlog;

use Throwable;

class ThrowableProcessor
{
    public function __invoke(array $record)
    {
        if (!$record['message'] instanceof Throwable) {
            return $record;
        }

        /** @var Throwable $throwable */
        $throwable = $record['message'];

        $record['message'] = $throwable->getMessage();
        $record['context']['exception'] = $throwable;

        return $record;
    }
}
