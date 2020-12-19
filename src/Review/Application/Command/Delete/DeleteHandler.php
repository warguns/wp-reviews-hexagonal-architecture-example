<?php

declare( strict_types=1 );

namespace BetterReview\Review\Application\Command\Delete;

use BetterReview\Review\Domain\Event\ReviewDeleted;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Shared\Domain\Service\EventDispatcher;
use Ramsey\Uuid\Uuid;

/**
 * Class DeleteHandler
 *
 * @package BetterReview\Review\Application\Command\Delete
 */
final class DeleteHandler {
	/**
	 * Repo.
	 *
	 * @var ReviewRepository repo.
	 */
	private $review_repository;

	/**
	 * Event Dispatcher.
	 *
	 * @var EventDispatcher event dispatcher.
	 */
	private $event_dispatcher;

	/**
	 * DeleteHandler constructor.
	 *
	 * @param ReviewRepository $review_repository repo.
	 * @param EventDispatcher  $event_dispatcher  Dispatcher.
	 */
	public function __construct( ReviewRepository $review_repository, EventDispatcher $event_dispatcher ) {
		$this->review_repository = $review_repository;
		$this->event_dispatcher  = $event_dispatcher;
	}

	/**
	 * Execution.
	 *
	 * @param DeleteCommand $command command.
	 *
	 * @throws IncorrectStars Incorrect stars.
	 * @throws ReviewNotFound When Review is not found.
	 * @throws StatusNotFound StatusNotFound.
	 */
	public function run( DeleteCommand $command ): void {
		$review = $this->review_repository->get( Uuid::fromString( $command->get_uuid() ) );

		$this->review_repository->delete( Uuid::fromString( $command->get_uuid() ) );

		if ( $review->get_status()->is_published() ) {
			$this->event_dispatcher->dispatch(
				new ReviewDeleted(
					UUid::uuid4(),
					UUid::uuid4(),
					$review->get_product_id()->get_id(),
					$review->get_stars()->get_stars()
				)
			);
		}
	}
}
