<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\Exception;

use BetterReview\Review\Domain\Exception\IncorrectStars;
use Codeception\Test\Unit;

class IncorrectStarsTest extends Unit
{
    public function testItShouldShowMessage()
    {
        $incorrectStars = new IncorrectStars();

        self::assertEquals('Incorrect Review Punctuation', $incorrectStars->getMessage());
    }
}