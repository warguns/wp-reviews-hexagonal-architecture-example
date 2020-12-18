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

        self::assertEquals($correlationUuid, $event->getCorrelationUuid());
        self::assertEquals($parentUuid, $event->getParentUuid());
        self::assertEquals($postId, $event->getPostId());
        self::assertEquals($stars, $event->getStars());
        self::assertEquals($status, $event->getStatus());
    }
}