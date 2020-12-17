<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Command\Update;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Event\ReviewAdded;
use BetterReview\Review\Domain\Event\ReviewDeleted;
use BetterReview\Review\Domain\Event\ReviewUpdated;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Review\Domain\ValueObject\Email;
use BetterReview\Review\Domain\ValueObject\Status;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use BetterReview\Review\Domain\ValueObject\Stars;
use BetterReview\Shared\Domain\Service\EventDispatcher;
use Ramsey\Uuid\Uuid;

final class UpdateHandler
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

    public function run(UpdateCommand $command)
    {
        $oldReview = $this->reviewRepository->get(Uuid::fromString($command->getUuid()));
        $review = Review::build(
            Uuid::fromString($command->getUuid()),
            ProductId::fromInt($command->getPostId()),
            Status::fromStatus($command->getStatus()),
            $command->getAuthor(),
            $command->getContent(),
            $command->getTitle(),
            Email::fromString($command->getEmail()),
            Stars::fromResult($command->getStars())
        );

        $this->reviewRepository->update($review);


        if ($oldReview->getStatus()->isPendingOrRejected() && $review->getStatus()->isPublished()) {
            $this->eventDispatcher->dispatch(new ReviewAdded(
                UUid::uuid4(),
                UUid::uuid4(),
                $command->getPostId(),
                $command->getStatus(),
                $command->getStars()
            ));
        }

        if ($oldReview->getStatus()->equals($review->getStatus())) {
            $this->eventDispatcher->dispatch(new ReviewUpdated(
                UUid::uuid4(),
                UUid::uuid4(),
                $command->getPostId(),
                $command->getStatus(),
                $oldReview->getStars()->getStars(),
                $command->getStars()
            ));
        }

        if ($oldReview->getStatus()->isPublished() && $review->getStatus()->isPendingOrRejected()) {
            $this->eventDispatcher->dispatch(new ReviewDeleted(
                UUid::uuid4(),
                UUid::uuid4(),
                $command->getPostId(),
                $command->getStars()
            ));
        }

    }
}