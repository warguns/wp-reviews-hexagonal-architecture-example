<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Review\Domain\ValueObject;

use HexagonalReviews\Review\Domain\Exception\StatusNotFound;
use HexagonalReviews\Review\Domain\ValueObject\Status;
use Codeception\Test\Unit;

class StatusTest extends Unit
{
    public function testItShouldBeCreated()
    {
        $status = Status::from_status(Status::PUBLISHED);
        self::assertInstanceOf(Status::class, $status);
    }

    public function testItShouldManageTheValidStatuses()
    {
        $this->expectException(StatusNotFound::class);
        $status = Status::from_status('fakeStatus');
    }

    public function testItShouldCreateAPendingStatus()
    {
        $status = Status::new();

        self::assertEquals(Status::PENDING, $status->get_status());
    }

    public function testItShouldBeAbleToCheckIfPendingOrRejected()
    {
        $status = Status::new();
        self::assertTrue($status->is_pending_or_rejected());

        $status = Status::from_status(Status::REJECTED);
        self::assertTrue($status->is_pending_or_rejected());

        $status = Status::from_status(Status::PUBLISHED);
        self::assertFalse($status->is_pending_or_rejected());
    }

    public function testItShouldCheckPublished()
    {
        $status = Status::from_status(Status::PUBLISHED);
        self::assertTrue($status->is_published());

        $status = Status::new();
        self::assertFalse($status->is_published());
    }

    public function testItShouldCompareStatus()
    {
        $status = Status::from_status(Status::PUBLISHED);
        $statusPending = Status::new();

        self::assertFalse($status->equals($statusPending));
        self::assertTrue($status->equals($status));
    }
}