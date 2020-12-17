<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Query\All;

use BetterReview\Review\Domain\DTO\ListReviewsResponse;
use BetterReview\Review\Domain\Repository\ReviewRepository;

final class ListHandler
{
    /** @var ReviewRepository */
    private $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function run(ListQuery $query): ListReviewsResponse
    {
        return new ListReviewsResponse(
            $this->reviewRepository->all(
                $query->getLimit(),
                $query->getOffset(),
                $query->getOrderBy(),
                $query->getSearch()
            ),
            $this->reviewRepository->countAll($query->getSearch())
        );
    }

}