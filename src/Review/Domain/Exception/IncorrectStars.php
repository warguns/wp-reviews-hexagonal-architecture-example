<?php
/**
 * IncorrectStars
 *
 * @package Review
 */

declare( strict_types=1 );

namespace BetterReview\Review\Domain\Exception;

use Exception;

/**
 * Class IncorrectStars
 *
 * @package BetterReview\Review\Domain\Exception
 */
final class IncorrectStars extends Exception {
	/**
	 * IncorrectStars constructor.
	 */
	public function __construct() {
		parent::__construct( 'Incorrect Review Punctuation' );
	}
}
