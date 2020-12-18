<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\ValueObject;

use BetterReview\Review\Domain\Entity\Review;

final class ReviewCollection implements \Iterator
{
    /** @var array */
    private $collection;

    private function __construct(array $collection = [])
    {
        $this->collection = $collection;
    }

    public static function fromResults(array $results): self
    {
        $reviewArray = [];
        foreach ($results as $result) {
            $reviewArray[] = Review::fromResult($result);
        }

        return new ReviewCollection($reviewArray);
    }

    public function current()
    {
        return current($this->collection);
    }

    public function next()
    {
        return next($this->collection);
    }

    public function key()
    {
        return key($this->collection);
    }

    public function valid()
    {
        return current($this->collection);
    }

    public function rewind()
    {
        return reset($this->collection);
    }

    public function toArray(): array
    {
        $collection = [];
        /** @var Review $review */
        foreach($this->collection as $review) {
            $collection[] = $review->toArray();
        }

        return $collection;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function getPostIds(): array
    {
        $ids = [];
        /** @var Review $review */
        foreach ($this->collection as $review) {
            $ids[] = $review->getPostId()->getId();
        }

        return $ids;
    }
}