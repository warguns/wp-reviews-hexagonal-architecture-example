<?php
/**
 * AverageCalculator
 *
 * @package Average
 */

declare( strict_types=1 );

namespace HexagonalReviews\Average\Domain\Service;

use HexagonalReviews\Average\Domain\DTO\ReviewStats;
use HexagonalReviews\Average\Domain\Entity\Average;

/**
 * Class AverageCalculator
 *
 * @package HexagonalReviews\Average\Domain\Service
 */
final class AverageCalculator {

	/**
	 * Calculates the average.
	 *
	 * @param Average|null $average average.
	 *
	 * @return ReviewStats
	 */
	public function calculate( ?Average $average ): ReviewStats {
		if ( null === $average ) {
			return new ReviewStats();
		}

		return new ReviewStats( $average->get_review_count(), ( $average->get_review_count() > 0 ) ? $average->get_total_review() / $average->get_review_count() : 0 );
	}
}
