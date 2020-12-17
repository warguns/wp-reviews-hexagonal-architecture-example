<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\ValueObject;

use BetterReview\Review\Domain\ValueObject\Email;

class EmailTest extends \Codeception\Test\Unit
{
    private const TEST_TEST_COM = 'test@test.com';

    public function testFromString()
    {
        $email = Email::fromString(self::TEST_TEST_COM);

        $this->assertEquals(self::TEST_TEST_COM, $email->getEmail());
    }

}
