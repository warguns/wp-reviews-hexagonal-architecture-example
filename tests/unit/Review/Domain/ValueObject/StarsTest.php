<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\ValueObject;

use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\ValueObject\Stars;

class StarsTest extends \Codeception\Test\Unit
{
    public function testItCanBeCreated()
    {
        $stars = Stars::from_result(4.4);
        self::assertEquals(4.4, $stars->get_stars());
    }

    public function testItCannotBeLessThanZero()
    {
        $this->expectException(IncorrectStars::class);
        $stars = Stars::from_result(-1);
    }

    public function testItCannotBeMoreThanFive()
    {
        $this->expectException(IncorrectStars::class);
        $stars = Stars::from_result(6);
    }
}