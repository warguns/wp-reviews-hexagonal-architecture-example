<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\Entity;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\ValueObject\Email;
use BetterReview\Review\Domain\ValueObject\Stars;
use BetterReview\Review\Domain\ValueObject\Status;
use BetterReview\Shared\Domain\ValueObject\ProductId;
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
            ProductId::fromInt($postId),
            Status::new(),
            $author,
            $content,
            $title,
            Email::fromString($email),
            Stars::fromResult($stars)
        );

        self::assertEquals([
            'uuid' => $review->getUuid()->toString(),
            'post_id' => $postId,
            'status' => 'pending',
            'author' => $author,
            'title' => $title,
            'content' => $content,
            'email' => $email,
            'stars' => $stars,
            'created_at' => $review->getCreatedAt()->format(DATE_ATOM),
            'updated_at' => $review->getUpdatedAt()->format(DATE_ATOM)
        ], $review->toArray());
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

        $review = Review::fromResult([
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
        ], $review->toArray());
    }
}