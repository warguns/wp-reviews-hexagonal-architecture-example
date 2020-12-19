<?php

declare( strict_types=1 );

namespace BetterReview\Review\Domain\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;
use Throwable;

/**
 * Class ReviewNotFound
 *
 * @package BetterReview\Review\Domain\Exception
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
