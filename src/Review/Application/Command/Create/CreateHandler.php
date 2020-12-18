<?php

declare(strict_types=1);

namespace BetterReview\Review\Application\Command\Create;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Event\ReviewAdded;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Review\Domain\ValueObject\Email;
use BetterReview\Review\Domain\ValueObject\Stars;
use BetterReview\Review\Domain\ValueObject\Status;
use BetterReview\Shared\Domain\Service\EventDispatcher;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use Ramsey\Uuid\Uuid;

final class CreateHandler
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

    public function run(CreateCommand $command)
    {
        $review = Review::build(
            Uuid::uuid4(),
            ProductId::fromInt($command->getPostId()),
            Status::new(),
            $command->getAuthor(),
            $command->getContent(),
            $command->getTitle(),
            Email::fromString($command->getEmail()),
            Stars::fromResult($command->getStars())
        );

        $this->reviewRepository->insert($review);

        if ($review->getStatus()->isPublished()) {
            $this->eventDispatcher->dispatch(
                new ReviewAdded(
                    UUid::uuid4(),
                    UUid::uuid4(),
                    $review->getPostId()->getId(),
                    $review->getStatus()->getStatus(),
                    $review->getStars()->getStars()
                )
            );
        }
    }
}