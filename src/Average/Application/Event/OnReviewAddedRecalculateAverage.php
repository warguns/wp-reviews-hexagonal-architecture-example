<?php

declare( strict_types=1 );

namespace BetterReview\Average\Application\Event;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Review\Domain\Event\ReviewAdded;
use BetterReview\Shared\Domain\ValueObject\ProductId;

/**
 * Class OnReviewAddedRecalculateAverage
 *
 * @package BetterReview\Average\Application\Event
 */
final class OnReviewAddedRecalculateAverage {
	/**
	 * Repo.
	 *
	 * @var AverageRepository
	 */
	private $average_repository;

	/**
	 * OnReviewAddedRecalculateAverage constructor.
	 *
	 * @param AverageRepository $average_repository repo.
	 */
	public function __construct( AverageRepository $average_repository ) {
		$this->average_repository = $average_repository;
	}

	/**
	 * Invocation.
	 *
	 * @param ReviewAdded $event event.
	 */
	public function __invoke( ReviewAdded $event ): void {
		$average = $this->average_repository->find( ProductId::from_int( $event->get_product_id() ) );

		if ( null === $average ) {
			$this->average_repository->insert(
				new Average(
					ProductId::from_int( $event->get_product_id() ),
					1,
					$event->get_stars()
				)
			);

			return;
		}

		$this->average_repository->update(
			new Average(
				ProductId::from_int( $event->get_product_id() ),
				$average->get_review_count() + 1,
				$average->get_total_review() + $event->get_stars()
			)
		);

	}
}
