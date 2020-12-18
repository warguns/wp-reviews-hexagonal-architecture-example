<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\ValueObject;

use BetterReview\Review\Domain\Exception\IncorrectStars;

final class Stars
{
    private const MIN_STARS = 0;
    private const MAX_STARS = 5;

    /** @var float */
    private $stars;

    private function __construct(float $stars)
    {
        $this->stars = $stars;
    }

    public static function fromResult(float $stars): self
    {
        if ($stars > self::MAX_STARS || $stars < self::MIN_STARS) {
            throw new IncorrectStars();
        }

        return new static($stars);
    }

    /**
     * @return float
     */
    public function getStars(): float
    {
        return $this->stars;
    }
}