<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Average\Domain\Entity;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use Codeception\Test\Unit;

class AverageTest extends Unit
{
    private const PRODUCT_ID = 1;
    private const REVIEWCOUNT = 1;
    private const TOTALREVIEW = 4.9;

    public function testItCanBeCreated()
    {
        $average = new Average(
            ProductId::from_int(self::PRODUCT_ID),
            self::REVIEWCOUNT,
            self::TOTALREVIEW
        );
        self::assertEquals([
            'post_id' => self::PRODUCT_ID,
            'review_count' => self::REVIEWCOUNT,
            'total_review' => self::TOTALREVIEW,
        ], $average->to_array());
    }
}