<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Review\Domain\Event;

use HexagonalReviews\Review\Domain\Event\ReviewDeleted;
use HexagonalReviews\Review\Domain\ValueObject\Status;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;

class ReviewDeletedTest extends Unit
{
    public function testItCanBeCreated()
    {
        $correlationUuid = Uuid::uuid4();
        $parentUuid = Uuid::uuid4();
        $postId = 1;
        $stars = 5;
        $status = Status::PENDING;
        $event = new ReviewDeleted($correlationUuid, $parentUuid, $postId, $stars);

        self::assertEquals($correlationUuid, $event->get_correlation_uuid());
        self::assertEquals($parentUuid, $event->get_parent_uuid());
        self::assertEquals($postId, $event->get_product_id());
        self::assertEquals($stars, $event->get_stars());
    }
}