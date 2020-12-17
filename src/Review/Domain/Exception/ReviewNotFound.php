<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

final class ReviewNotFound extends \Exception
{
    public function __construct(UuidInterface $uuid, $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Review not found: {$uuid->toString()}", $code, $previous);
    }
}