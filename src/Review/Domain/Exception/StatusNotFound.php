<?php
/**
 * StatusNotFound
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Domain\Exception;

use Exception;
use Throwable;

/**
 * Class StatusNotFound
 *
 * @package HexagonalReviews\Review\Domain\Exception
 */
final class StatusNotFound extends Exception {

	/**
	 * StatusNotFound constructor.
	 *
	 * @param string         $status status.
	 * @param int            $code code.
	 * @param Throwable|null $previous previous.
	 */
	public function __construct( string $status, $code = 0, Throwable $previous = null ) {
		parent::__construct( "Status not found: {$status}", $code, $previous );
	}
}
