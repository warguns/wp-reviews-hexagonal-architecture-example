<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Query\Get;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use Ramsey\Uuid\Uuid;

final class GetHandler
{
    /** @var ReviewRepository */
    private $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function run(GetQuery $query): Review
    {
        return $this->reviewRepository->get(Uuid::fromString($query->getUuid()));
    }
}