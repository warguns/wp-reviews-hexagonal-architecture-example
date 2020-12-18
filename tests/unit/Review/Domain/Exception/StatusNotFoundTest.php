<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\Exception;

use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Review\Domain\ValueObject\Status;
use Codeception\Test\Unit;


class StatusNotFoundTest extends Unit
{
    public function testItShouldShowMessage()
    {
        $status = Status::PENDING;
        $exception = new StatusNotFound($status);

        self::assertEquals("Status not found: {$status}", $exception->getMessage());
    }
}