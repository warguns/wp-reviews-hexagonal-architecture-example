<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\Event;

use BetterReview\Shared\Domain\Event\Event;
use Ramsey\Uuid\UuidInterface;

final class ReviewUpdated implements Event
{
    /** @var UuidInterface */
    private $correlationUuid;

    /** @var UuidInterface */
    private $parentUuid;

    /** @var int */
    private $postId;

    /** @var string */
    private $status;

    /** @var float */
    private $oldStars;

    /** @var float */
    private $stars;

    public function __construct(UuidInterface $correlationUuid, UuidInterface $parentUuid, int $postId, string $status, float $oldStars, float $stars)
    {
        $this->correlationUuid = $correlationUuid;
        $this->parentUuid = $parentUuid;
        $this->postId = $postId;
        $this->status = $status;
        $this->oldStars = $oldStars;
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

    public function getOldStars(): float
    {
        return $this->oldStars;
    }

    public function getStars(): float
    {
        return $this->stars;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}