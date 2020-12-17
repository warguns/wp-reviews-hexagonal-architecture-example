<?php

declare(strict_types=1);

namespace BetterReview\Average\Application\Event;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Review\Domain\Event\ReviewAdded;
use BetterReview\Shared\Domain\ValueObject\ProductId;

final class OnReviewAddedRecalculateAverage
{
    /** @var AverageRepository */
    private $averageRepository;

    public function __construct(AverageRepository $averageRepository)
    {
        $this->averageRepository = $averageRepository;
    }

    public function __invoke(ReviewAdded $event): void
    {
        $average = $this->averageRepository->find(ProductId::fromInt($event->getPostId()));

        if (null === $average) {
            $this->averageRepository->insert(new Average(
                ProductId::fromInt($event->getPostId()),
                1,
                $event->getStars()
            ));
            return;
        }

        $this->averageRepository->update(new Average(
            ProductId::fromInt($event->getPostId()),
            $average->getReviewCount() + 1,
            $average->getTotalReview() + $event->getStars()
        ));

    }
}