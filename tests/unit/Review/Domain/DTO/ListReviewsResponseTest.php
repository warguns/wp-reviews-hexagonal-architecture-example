<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Review\Domain\DTO;

use HexagonalReviews\Review\Domain\DTO\ListReviewsResponse;
use HexagonalReviews\Review\Domain\ValueObject\ReviewCollection;


class ListReviewsResponseTest extends \Codeception\Test\Unit
{
    private const TOTALS = 3;
    
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testItShouldBeConstructed()
    {
        $reviewCollection = ReviewCollection::from_results([]);
        $reviewResponse = new ListReviewsResponse($reviewCollection, self::TOTALS);

        self::assertEquals($reviewCollection, $reviewResponse->get_review_collection());
        self::assertEquals(self::TOTALS, $reviewResponse->get_totals());
    }

}