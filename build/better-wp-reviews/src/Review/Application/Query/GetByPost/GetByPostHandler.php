<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Query\GetByPost;

use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;

final class GetByPostHandler
{
    /** @var ReviewRepository */
    private $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function run(GetByPostQuery $query): ReviewCollection
    {
        return $this->reviewRepository->findByPost(ProductId::fromInt($query->getId()), $query->getLimit(), $query->getOffset());
    }
}