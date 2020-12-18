<?php

declare(strict_types=1);

namespace BetterReview\Average\Domain\Service;

use BetterReview\Average\Domain\DTO\ReviewStats;
use BetterReview\Average\Domain\Entity\Average;

final class AverageCalculator
{
    public function calculate(?Average $average): ReviewStats
    {
        if (null === $average) {
            return new ReviewStats();
        }

        return new ReviewStats($average->getReviewCount(), ($average->getReviewCount() > 0) ? $average->getTotalReview() / $average->getReviewCount() : 0);
    }
}