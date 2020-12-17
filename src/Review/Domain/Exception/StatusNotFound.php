<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\Exception;

final class StatusNotFound extends \Exception
{
    public function __construct(string $status, $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Status not found: {$status}", $code, $previous);
    }
}