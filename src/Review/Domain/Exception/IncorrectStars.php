<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\Exception;

use Throwable;

final class IncorrectStars extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Incorrect Review Punctuation', $code, $previous);
    }
}