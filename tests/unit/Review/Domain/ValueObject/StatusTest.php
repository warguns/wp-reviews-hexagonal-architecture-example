<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\ValueObject;

use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Review\Domain\ValueObject\Status;
use Codeception\Test\Unit;

class StatusTest extends Unit
{
    public function testItShouldBeCreated()
    {
        $status = Status::fromStatus(Status::PUBLISHED);
        self::assertInstanceOf(Status::class, $status);
    }

    public function testItShouldManageTheValidStatuses()
    {
        $this->expectException(StatusNotFound::class);
        $status = Status::fromStatus('fakeStatus');
    }

    public function testItShouldCreateAPendingStatus()
    {
        $status = Status::new();

        self::assertEquals(Status::PENDING, $status->getStatus());
    }

    public function testItShouldBeAbleToCheckIfPendingOrRejected()
    {
        $status = Status::new();
        self::assertTrue($status->isPendingOrRejected());

        $status = Status::fromStatus(Status::REJECTED);
        self::assertTrue($status->isPendingOrRejected());

        $status = Status::fromStatus(Status::PUBLISHED);
        self::assertFalse($status->isPendingOrRejected());
    }

    public function testItShouldCheckPublished()
    {
        $status = Status::fromStatus(Status::PUBLISHED);
        self::assertTrue($status->isPublished());

        $status = Status::new();
        self::assertFalse($status->isPublished());
    }

    public function testItShouldCompareStatus()
    {
        $status = Status::fromStatus(Status::PUBLISHED);
        $statusPending = Status::new();

        self::assertFalse($status->equals($statusPending));
        self::assertTrue($status->equals($status));
    }
}