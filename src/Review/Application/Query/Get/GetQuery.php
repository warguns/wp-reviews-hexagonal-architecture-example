<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Query\Get;

final class GetQuery
{
    /** @var string */
    private $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}