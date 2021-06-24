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
	 * ZSCORE
	 *
	 * @var float
	 */
	private const ZSCORE = 1.96;

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

		return new ReviewStats( $average->get_review_count(), self::lower_bound_of_wilson_score_confidence( $average->get_positives(), $average->get_review_count() ) );
	}

	/**
	 * Score calculated with the Wilson score confidence
	 * more information at:  https://www.evanmiller.org/how-not-to-sort-by-average-rating.html
	 *
	 * @param float $positive number of positive reviews.
	 * @param float $totals total reviews.
	 *
	 * @return float final result.
	 */
	private static function lower_bound_of_wilson_score_confidence( float $positive, float $totals ): float {
		$phat = ( 1.0 * $positive ) / $totals;
		return 0. === $totals ? 0.
			: ( $phat + ( self::ZSCORE * self::ZSCORE ) / ( 2 * $totals ) - self::ZSCORE * sqrt( ( $phat * ( 1 - $phat ) + ( self::ZSCORE * self::ZSCORE ) / ( 4 * $totals ) ) / $totals ) ) / ( 1 + ( self::ZSCORE * self::ZSCORE ) / $totals ) * 5;
	}

	/**
	 * Evaluates if a review is positive
	 *
	 * @param float $stars number of review stars.
	 *
	 * @return float 1 or 0 to sum if it's positive or not.
	 */
	public static function check_if_its_positive_review( float $stars ) : float {
		return ( $stars > 2 ) ? 1. : 0.;
	}
}
