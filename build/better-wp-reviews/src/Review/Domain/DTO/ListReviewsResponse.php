<?php

declare(strict_types=1);

namespace BetterReview\Review\Domain\DTO;

use BetterReview\Review\Domain\ValueObject\ReviewCollection;

final class ListReviewsResponse
{
    /** @var ReviewCollection */
    private $reviewCollection;

    /** @var int */
    private $totals;

    public function __construct(ReviewCollection $reviewCollection, int $totals)
    {
        $this->reviewCollection = $reviewCollection;
        $this->totals = $totals;
    }

    /**
     * @return ReviewCollection
     */
    public function getReviewCollection(): ReviewCollection
    {
        return $this->reviewCollection;
    }

    /**
     * @return int
     */
    public function getTotals(): int
    {
        return $this->totals;
    }
}