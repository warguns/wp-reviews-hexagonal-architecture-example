<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\DTO;

use BetterReview\Review\Domain\DTO\ListReviewsResponse;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;


class ListReviewsResponseTest extends \Codeception\Test\Unit
{
    private const TOTALS = 3;
    
    protected function setUp()
    {
        parent::setUp();
    }

    public function testItShouldBeConstructed()
    {
        $reviewCollection = ReviewCollection::fromResults([]);
        $reviewResponse = new ListReviewsResponse($reviewCollection, self::TOTALS);

        self::assertEquals($reviewCollection, $reviewResponse->getReviewCollection());
        self::assertEquals(self::TOTALS, $reviewResponse->getTotals());
    }

}