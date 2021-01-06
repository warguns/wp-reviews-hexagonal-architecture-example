<?php
/**
 * UpdateHandler
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Application\Command\Update;

use HexagonalReviews\Review\Domain\Entity\Review;
use HexagonalReviews\Review\Domain\Event\ReviewAdded;
use HexagonalReviews\Review\Domain\Event\ReviewDeleted;
use HexagonalReviews\Review\Domain\Event\ReviewUpdated;
use HexagonalReviews\Review\Domain\Exception\IncorrectStars;
use HexagonalReviews\Review\Domain\Exception\ReviewNotFound;
use HexagonalReviews\Review\Domain\Exception\StatusNotFound;
use HexagonalReviews\Review\Domain\Repository\ReviewRepository;
use HexagonalReviews\Review\Domain\ValueObject\Email;
use HexagonalReviews\Review\Domain\ValueObject\Stars;
use HexagonalReviews\Review\Domain\ValueObject\Status;
use HexagonalReviews\Shared\Domain\Service\EventDispatcher;
use HexagonalReviews\Shared\Domain\ValueObject\ProductId;
use Ramsey\Uuid\Uuid;

/**
 * Class UpdateHandler
 *
 * @package HexagonalReviews\Review\Application\Command\Update
 */
final class UpdateHandler {

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
	 * UpdateHandler constructor.
	 *
	 * @param ReviewRepository $review_repository repo.
	 * @param EventDispatcher  $event_dispatcher dispatcher.
	 */
	public function __construct( ReviewRepository $review_repository, EventDispatcher $event_dispatcher ) {
		$this->review_repository = $review_repository;
		$this->event_dispatcher  = $event_dispatcher;
	}

	/**
	 * Execution.
	 *
	 * @param UpdateCommand $command command.
	 *
	 * @throws IncorrectStars Incorrect stars.
	 * @throws ReviewNotFound No review.
	 * @throws StatusNotFound Incorrect status.
	 */
	public function run( UpdateCommand $command ) {
		$old_review = $this->review_repository->get( Uuid::fromString( $command->get_uuid() ) );
		$review     = Review::build(
			Uuid::fromString( $command->get_uuid() ),
			ProductId::from_int( $command->get_post_id() ),
			Status::from_status( $command->get_status() ),
			$command->get_author(),
			$command->get_content(),
			$command->get_title(),
			Email::from_string( $command->get_email() ),
			Stars::from_result( $command->get_stars() )
		);

		$this->review_repository->update( $review );

		if ( $old_review->get_status()->is_pending_or_rejected() && $review->get_status()->is_published() ) {
			$this->event_dispatcher->dispatch(
				new ReviewAdded(
					UUid::uuid4(),
					UUid::uuid4(),
					$command->get_post_id(),
					$command->get_status(),
					$command->get_stars()
				)
			);
		}

		if ( $old_review->get_status()->is_published() && $old_review->get_status()->equals( $review->get_status() ) ) {
			$this->event_dispatcher->dispatch(
				new ReviewUpdated(
					UUid::uuid4(),
					UUid::uuid4(),
					$command->get_post_id(),
					$command->get_status(),
					$old_review->get_stars()->get_stars(),
					$command->get_stars()
				)
			);
		}

		if ( $old_review->get_status()->is_published() && $review->get_status()->is_pending_or_rejected() ) {
			$this->event_dispatcher->dispatch(
				new ReviewDeleted(
					UUid::uuid4(),
					UUid::uuid4(),
					$command->get_post_id(),
					$command->get_stars()
				)
			);
		}

	}
}
