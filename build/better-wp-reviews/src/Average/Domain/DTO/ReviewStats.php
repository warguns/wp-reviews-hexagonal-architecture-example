<?php

declare(strict_types=1);

namespace BetterReview\Average\Domain\DTO;

final class ReviewStats
{
    /** @var int */
    private $reviewCount;

    /** @var float */
    private $average;

    public function __construct(int $reviewCount = 0, float $average = 0)
    {
        $this->reviewCount = $reviewCount;
        $this->average = $average;
    }

    public function getReviewCount(): int
    {
        return $this->reviewCount;
    }

    public function getAverage(): float
    {
        return $this->average;
    }
}