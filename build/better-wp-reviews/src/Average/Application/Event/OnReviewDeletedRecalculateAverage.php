<?php

declare(strict_types=1);

namespace BetterReview\Average\Application\Event;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Review\Domain\Event\ReviewDeleted;
use BetterReview\Shared\Domain\ValueObject\ProductId;

final class OnReviewDeletedRecalculateAverage
{
    /** @var AverageRepository */
    private $averageRepository;

    public function __construct(AverageRepository $averageRepository)
    {
        $this->averageRepository = $averageRepository;
    }

    public function __invoke(ReviewDeleted $event): void
    {
        $average = $this->averageRepository->find(ProductId::fromInt($event->getPostId()));

        $this->averageRepository->update(new Average(
            ProductId::fromInt($event->getPostId()),
            $average->getReviewCount() - 1,
            $average->getTotalReview() - $event->getStars()
        ));

    }
}