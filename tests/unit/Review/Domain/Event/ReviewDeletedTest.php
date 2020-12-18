<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\Event;

use BetterReview\Review\Domain\Event\ReviewDeleted;
use BetterReview\Review\Domain\ValueObject\Status;
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

        self::assertEquals($correlationUuid, $event->getCorrelationUuid());
        self::assertEquals($parentUuid, $event->getParentUuid());
        self::assertEquals($postId, $event->getPostId());
        self::assertEquals($stars, $event->getStars());
    }
}