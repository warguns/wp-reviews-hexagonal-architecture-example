<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Review\Domain\ValueObject;

use HexagonalReviews\Review\Domain\Exception\IncorrectStars;
use HexagonalReviews\Review\Domain\ValueObject\Stars;

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