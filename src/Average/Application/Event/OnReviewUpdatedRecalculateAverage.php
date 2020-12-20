<?php
/**
 * OnReviewUpdatedRecalculateAverage
 *
 * @package Average
 */

declare( strict_types=1 );

namespace BetterReview\Average\Application\Event;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Review\Domain\Event\ReviewUpdated;
use BetterReview\Shared\Domain\ValueObject\ProductId;

/**
 * Class OnReviewUpdatedRecalculateAverage
 *
 * @package BetterReview\Average\Application\Event
 */
final class OnReviewUpdatedRecalculateAverage {

	/**
	 * Repo.
	 *
	 * @var AverageRepository
	 */
	private $average_repository;

	/**
	 * OnReviewUpdatedRecalculateAverage constructor.
	 *
	 * @param AverageRepository $average_repository repo.
	 */
	public function __construct( AverageRepository $average_repository ) {
		$this->average_repository = $average_repository;
	}

	/**
	 * Invocation.
	 *
	 * @param ReviewUpdated $event event.
	 *
	 * @return ReviewUpdated
	 */
	public function __invoke( ReviewUpdated $event ) {
		$average = $this->average_repository->find( ProductId::from_int( $event->get_product_id() ) );

		$this->average_repository->update(
			new Average(
				ProductId::from_int( $event->get_product_id() ),
				$average->get_review_count(),
				$average->get_total_review() + $event->get_stars() - $event->get_old_stars()
			)
		);

		return $event;
	}
}
