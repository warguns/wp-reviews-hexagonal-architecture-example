<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Average\Domain\Entity;

use HexagonalReviews\Average\Domain\Entity\Average;
use HexagonalReviews\Shared\Domain\ValueObject\ProductId;
use Codeception\Test\Unit;

class AverageTest extends Unit
{
    private const PRODUCT_ID = 1;
    private const REVIEWCOUNT = 1;
    private const POSITIVES = 4.9;

    public function testItCanBeCreated()
    {
        $average = new Average(
            ProductId::from_int(self::PRODUCT_ID),
            self::REVIEWCOUNT,
            self::POSITIVES
        );
        self::assertEquals([
            'post_id' => self::PRODUCT_ID,
            'review_count' => self::REVIEWCOUNT,
            'positives' => self::POSITIVES,
        ], $average->to_array());
    }
}