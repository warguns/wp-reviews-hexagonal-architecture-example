<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\Repository;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;
use Ramsey\Uuid\UuidInterface;

interface ReviewRepository
{
    public const LIMIT = 100;
    public const OFFSET = 0;

    /**
     * @throws ReviewNotFound
     */
    public function get(UuidInterface $reviewUuid): Review;

    public function insert(Review $review): bool;

    public function update(Review $review): bool;

    public function delete(UuidInterface $reviewUuid): bool;

    public function findByPost(ProductId $postId, int $limit = self::LIMIT, int $offset = self::OFFSET): ReviewCollection;

    public function all(int $limit = self::LIMIT, int $offset = self::OFFSET, array $orderby = [], string $search = null): ReviewCollection;

    public function countAll(string $search = null): int;
}