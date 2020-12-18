<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\Exception;

use BetterReview\Review\Domain\Exception\ReviewNotFound;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;

class ReviewNotFoundTest extends Unit
{
    public function testItShouldShowMessage()
    {
        $uuid = Uuid::uuid4();
        $exception = new ReviewNotFound($uuid);

        self::assertEquals("Review not found: {$uuid->toString()}", $exception->getMessage());
    }
}