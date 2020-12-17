<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Command\Delete;

use BetterReview\Review\Domain\Event\ReviewDeleted;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Shared\Domain\Service\EventDispatcher;
use Ramsey\Uuid\Uuid;

final class DeleteHandler
{
    /** @var ReviewRepository */
    private $reviewRepository;

    /** @var EventDispatcher */
    private $eventDispatcher;

    public function __construct(ReviewRepository $reviewRepository, EventDispatcher $eventDispatcher)
    {
        $this->reviewRepository = $reviewRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function run(DeleteCommand $command): void
    {
        $review = $this->reviewRepository->get(Uuid::fromString($command->getUuid()));

        $this->reviewRepository->delete(Uuid::fromString($command->getUuid()));

        if ($review->getStatus()->isPublished()) {
            $this->eventDispatcher->dispatch(new ReviewDeleted(
                UUid::uuid4(),
                UUid::uuid4(),
                $review->getPostId()->getId(),
                $review->getStars()->getStars()
            ));
        }
    }
}