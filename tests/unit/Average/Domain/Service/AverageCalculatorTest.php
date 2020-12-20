<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Average\Domain\Service;

use BetterReview\Average\Domain\DTO\ReviewStats;
use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Average\Domain\Service\AverageCalculator;
use Codeception\Test\Unit;

class AverageCalculatorTest extends Unit
{
    public function testItShouldPutDefaultAverage()
    {
        $averageCalculator = new AverageCalculator();
        $average = $averageCalculator->calculate(null);
        self::assertEquals(new ReviewStats(), $average);
        self::assertEquals(0, $average->get_review_count());
    }

    public function testItShouldCalculateAverage()
    {
        $average = new AverageCalculator();
        self::assertEquals(new ReviewStats(2, 5), $average->calculate(Average::from_result([
            'post_id' => 1,
            'review_count' => 2,
            'total_review' => 10,
        ])));
    }
}