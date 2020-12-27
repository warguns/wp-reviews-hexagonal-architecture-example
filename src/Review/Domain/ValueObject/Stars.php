<?php
/**
 * Stars
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Domain\ValueObject;

use HexagonalReviews\Review\Domain\Exception\IncorrectStars;

/**
 * Class Stars
 *
 * @package HexagonalReviews\Review\Domain\ValueObject
 */
final class Stars {
	private const MIN_STARS = 0;
	private const MAX_STARS = 5;

	/**
	 * Stars.
	 *
	 * @var float stars.
	 */
	private $stars;

	/**
	 * Stars constructor.
	 *
	 * @param float $stars stars.
	 */
	private function __construct( float $stars ) {
		$this->stars = $stars;
	}

	/**
	 * Creates From result.
	 *
	 * @param float $stars stars.
	 *
	 * @return static
	 * @throws IncorrectStars Incorrect stars.
	 */
	public static function from_result( float $stars ): self {
		if ( $stars > self::MAX_STARS || $stars < self::MIN_STARS ) {
			throw new IncorrectStars();
		}

		return new static( $stars );
	}

	/**
	 * Gets stars.
	 *
	 * @return float
	 */
	public function get_stars(): float {
		return $this->stars;
	}
}
