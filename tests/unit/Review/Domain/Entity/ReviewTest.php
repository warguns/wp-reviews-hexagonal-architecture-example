<?php

declare(strict_types=1);

namespace HexagonalReviews\Tests\unit\Review\Domain\Entity;

use HexagonalReviews\Review\Domain\Entity\Review;
use HexagonalReviews\Review\Domain\ValueObject\Email;
use HexagonalReviews\Review\Domain\ValueObject\Stars;
use HexagonalReviews\Review\Domain\ValueObject\Status;
use HexagonalReviews\Shared\Domain\ValueObject\ProductId;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;

class ReviewTest extends Unit
{
    public function testItCanBeBuilded()
    {
        $postId = 1;
        $stars = 5;
        $author = 'Testazo';
        $content = 'Content';
        $title = 'Title';
        $email = 'test@test.com';

        $review = Review::build(
            Uuid::uuid4(),
            ProductId::from_int($postId),
            Status::new(),
            $author,
            $content,
            $title,
            Email::from_string($email),
            Stars::from_result($stars)
        );

        self::assertEquals([
            'uuid' => $review->get_uuid()->toString(),
            'post_id' => $postId,
            'status' => 'pending',
            'author' => $author,
            'title' => $title,
            'content' => $content,
            'email' => $email,
            'stars' => $stars,
            'created_at' => $review->get_created_at()->format(DATE_ATOM),
            'updated_at' => $review->get_updated_at()->format(DATE_ATOM)
        ], $review->to_array());
    }

    public function testItCanBeCreatedFromResult()
    {
        $uuid = Uuid::uuid4()->toString();
        $postId = 1;
        $stars = 5;
        $author = 'Testazo';
        $content = 'Content';
        $title = 'Title';
        $email = 'test@test.com';

        $review = Review::from_result([
            'uuid' => $uuid,
            'post_id' => $postId,
            'status' => 'pending',
            'author' => $author,
            'title' => $title,
            'content' => $content,
            'email' => $email,
            'stars' => $stars,
            'created_at' => (new \DateTime())->format(DATE_ATOM),
            'updated_at' => (new \DateTime())->format(DATE_ATOM)
        ]);

        self::assertEquals([
            'uuid' => $uuid,
            'post_id' => $postId,
            'status' => 'pending',
            'author' => $author,
            'title' => $title,
            'content' => $content,
            'email' => $email,
            'stars' => $stars,
            'created_at' => (new \DateTime())->format(DATE_ATOM),
            'updated_at' => (new \DateTime())->format(DATE_ATOM)
        ], $review->to_array());
    }
}