<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Review\Domain\Event;

use HexagonalReviews\Review\Domain\Event\ReviewUpdated;
use HexagonalReviews\Review\Domain\ValueObject\Status;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;

class ReviewUpdatedTest extends Unit
{
    public function testItCanBeCreated()
    {
        $correlationUuid = Uuid::uuid4();
        $parentUuid = Uuid::uuid4();
        $postId = 1;
        $oldStars = 0;
        $stars = 5;
        $status = Status::PENDING;
        $event = new ReviewUpdated($correlationUuid, $parentUuid, $postId, $status, $oldStars, $stars);

        self::assertEquals($correlationUuid, $event->get_correlation_uuid());
        self::assertEquals($parentUuid, $event->get_parent_uuid());
        self::assertEquals($postId, $event->get_product_id());
        self::assertEquals($stars, $event->get_stars());
        self::assertEquals($oldStars, $event->get_old_stars());
        self::assertEquals($status, $event->get_status());
    }
}