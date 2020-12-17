<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Command\Delete;

use Ramsey\Uuid\UuidInterface;

final class DeleteCommand
{
    /** @var string */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}