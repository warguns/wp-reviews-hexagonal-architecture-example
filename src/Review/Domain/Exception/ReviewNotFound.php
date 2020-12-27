<?php
/**
 * ReviewNotFound
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Domain\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;
use Throwable;

/**
 * Class ReviewNotFound
 *
 * @package HexagonalReviews\Review\Domain\Exception
 */
final class ReviewNotFound extends Exception {

	/**
	 * ReviewNotFound constructor.
	 *
	 * @param UuidInterface  $uuid uuid.
	 * @param int            $code code.
	 * @param Throwable|null $previous previos.
	 */
	public function __construct( UuidInterface $uuid, $code = 0, Throwable $previous = null ) {
		parent::__construct( "Review not found: {$uuid->toString()}", $code, $previous );
	}
}
