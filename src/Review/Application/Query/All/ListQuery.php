<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Query\All;

final class ListQuery
{
    /** @var null|string */
    private $search;

    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    /** @var null|string */
    private $orderby;

    /** @var null|string */
    private $order;

    public function __construct(?string $search, int $limit, int $offset, ?string $orderby, ?string $order)
    {
        $this->search = $search;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderby = $orderby;
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getOrderBy(): array
    {
        if (null === $this->orderby) {
            return [];
        }
        return [$this->orderby => $this->order];
    }
}