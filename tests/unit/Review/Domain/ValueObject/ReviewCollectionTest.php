<?php

declare(strict_types=1);

namespace BetterReview\Tests\unit\Review\Domain\ValueObject;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;
use BetterReview\Review\Domain\ValueObject\Status;
use Codeception\Test\Unit;
use Ramsey\Uuid\Uuid;

class ReviewCollectionTest extends Unit
{
    public function testItShouldBeCountable()
    {
        $collection = ReviewCollection::fromResults([]);
        self::assertEquals(0, $collection->count());
    }
    
    public function testItShouldBeIterable()
    {
        $results = $this->getExampleResults();
        $collection = ReviewCollection::fromResults($results);
        $i = 0;
        /** @var Review $review */
        foreach ($collection as $review) {
            self::assertEquals($results[$i]['uuid'], $review->getUuid()->toString());
            $i++;
        }
    }

    public function testItShouldShowOnlyPostIds()
    {
        $results = $this->getExampleResults();
        $collection = ReviewCollection::fromResults($results);

        self::assertEquals([1, 2], $collection->getPostIds());
    }

    private function getExampleResults(): array
    {
        $result = [
            'uuid' => Uuid::uuid4()->toString(),
            'post_id' => 1,
            'status' => Status::PENDING,
            'author' => 'Testazo',
            'title' => 'Titulo',
            'content' => 'Content',
            'email' => 'test@test.com',
            'stars' => 5,
            'created_at' => (new \DateTime())->format(DATE_ATOM),
            'updated_at' => (new \DateTime())->format(DATE_ATOM)
        ];

        $result2 = [
            'uuid' => Uuid::uuid4()->toString(),
            'post_id' => 2,
            'status' => Status::PENDING,
            'author' => 'Testazo',
            'title' => 'Titulo',
            'content' => 'Content',
            'email' => 'test@test.com',
            'stars' => 2,
            'created_at' => (new \DateTime())->format(DATE_ATOM),
            'updated_at' => (new \DateTime())->format(DATE_ATOM)
        ];

        return [$result, $result2];
    }
}