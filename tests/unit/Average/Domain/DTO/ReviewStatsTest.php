<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Average\Domain\DTO;

use BetterReview\Average\Domain\DTO\ReviewStats;
use Codeception\Test\Unit;

class ReviewStatsTest extends Unit
{
    private const REVIEWCOUNT = 0;

    private const AVERAGE = 0;

    public function testItShouldBeConstructed()
    {
        $reviewStats = new ReviewStats(self::REVIEWCOUNT, self::AVERAGE);

        self::assertEquals(self::REVIEWCOUNT, $reviewStats->get_review_count());
        self::assertEquals(self::AVERAGE, $reviewStats->get_average());
    }
}