<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\Event;

use BetterReview\Review\Domain\Event\ReviewAdded;
use BetterReview\Review\Domain\ValueObject\Status;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;

class ReviewAddedTest extends Unit
{
    public function testItCanBeCreated()
    {
        $correlationUuid = Uuid::uuid4();
        $parentUuid = Uuid::uuid4();
        $postId = 1;
        $stars = 5;
        $status = Status::PENDING;
        $event = new ReviewAdded($correlationUuid, $parentUuid, $postId, $status, $stars);

        self::assertEquals($correlationUuid, $event->get_correlation_uuid());
        self::assertEquals($parentUuid, $event->get_parent_uuid());
        self::assertEquals($postId, $event->get_product_id());
        self::assertEquals($stars, $event->get_stars());
        self::assertEquals($status, $event->get_status());
    }
}