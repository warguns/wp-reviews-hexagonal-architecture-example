<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Query\GetByPost;

use BetterReview\Review\Domain\Repository\ReviewRepository;

final class GetByPostQuery
{
    /** @var int */
    private $id;

    /** @var ?int */
    private $limit;

    /** @var ?int */
    private $offset;

    public function __construct(int $id, int $limit = ReviewRepository::LIMIT, int $offset = ReviewRepository::OFFSET)
    {
        $this->id = $id;
        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }
}