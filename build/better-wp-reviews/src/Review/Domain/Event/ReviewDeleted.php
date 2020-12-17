<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\Event;

use BetterReview\Shared\Domain\Event\Event;
use Ramsey\Uuid\UuidInterface;

final class ReviewDeleted implements Event
{
    /** @var UuidInterface */
    private $correlationUuid;

    /** @var UuidInterface */
    private $parentUuid;

    /** @var int */
    private $postId;

    /** @var float */
    private $stars;

    public function __construct(UuidInterface $correlationUuid, UuidInterface $parentUuid, int $postId, float $stars)
    {
        $this->correlationUuid = $correlationUuid;
        $this->parentUuid = $parentUuid;
        $this->postId = $postId;
        $this->stars = $stars;
    }

    public function getParentUuid(): string
    {
        return $this->parentUuid->toString();
    }

    public function getCorrelationUuid(): string
    {
        return $this->correlationUuid->toString();
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getStars(): float
    {
        return $this->stars;
    }
}