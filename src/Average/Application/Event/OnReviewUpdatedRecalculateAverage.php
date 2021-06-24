<?php
/**
 * OnReviewUpdatedRecalculateAverage
 *
 * @package Average
 */

declare( strict_types=1 );

namespace HexagonalReviews\Average\Application\Event;

use HexagonalReviews\Average\Domain\Entity\Average;
use HexagonalReviews\Average\Domain\Repository\AverageRepository;
use HexagonalReviews\Average\Domain\Service\AverageCalculator;
use HexagonalReviews\Review\Domain\Event\ReviewUpdated;
use HexagonalReviews\Shared\Domain\ValueObject\ProductId;

/**
 * Class OnReviewUpdatedRecalculateAverage
 *
 * @package HexagonalReviews\Average\Application\Event
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

		if ( $average ) {
			$this->average_repository->update(
				new Average(
					ProductId::from_int( $event->get_product_id() ),
					$average->get_review_count(),
					$average->get_positives() + AverageCalculator::check_if_its_positive_review( $event->get_stars() ) - AverageCalculator::check_if_its_positive_review( $event->get_old_stars() )
				)
			);
		}

		return $event;
	}
}
