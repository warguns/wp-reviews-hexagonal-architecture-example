<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Review\Domain\ValueObject;

use HexagonalReviews\Review\Domain\ValueObject\Email;

class EmailTest extends \Codeception\Test\Unit
{
    private const TEST_TEST_COM = 'test@test.com';

    public function testFromString()
    {
        $email = Email::from_string(self::TEST_TEST_COM);

        $this->assertEquals(self::TEST_TEST_COM, $email->get_email());
    }

}
