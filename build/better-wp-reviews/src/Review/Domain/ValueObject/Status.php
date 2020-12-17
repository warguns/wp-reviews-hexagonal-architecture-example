<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\ValueObject;

use BetterReview\Review\Domain\Exception\StatusNotFound;

final class Status
{
    public const PENDING = 'pending';
    public const PUBLISHED = 'published';
    public const REJECTED = 'rejected';

    public const STATUS = [
        self::PENDING,
        self::PUBLISHED,
        self::REJECTED,
    ];

    /** @var string */
    private $status;

    private function __construct(string $status)
    {
        $this->status = $status;
    }

    public static function fromStatus(string $status): Status
    {
        if (!in_array($status, self::STATUS)) {
            throw new StatusNotFound($status);
        }

        return new static($status);
    }

    public static function new(): Status
    {
        return new static(self::PENDING);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPendingOrRejected(): bool
    {
        return $this->status === self::PENDING || $this->status === self::REJECTED;
    }

    public function isPublished(): bool
    {
        return $this->status === self::PUBLISHED;
    }

    public function equals(Status $status): bool
    {
        return $this->status === $status->getStatus();
    }
}