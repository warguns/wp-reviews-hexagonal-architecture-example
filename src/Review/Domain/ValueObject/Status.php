<?php
/**
 * Status
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Domain\ValueObject;

use HexagonalReviews\Review\Domain\Exception\StatusNotFound;

/**
 * Class Status
 *
 * @package HexagonalReviews\Review\Domain\ValueObject
 */
final class Status {
	public const PENDING   = 'pending';
	public const PUBLISHED = 'published';
	public const REJECTED  = 'rejected';

	public const STATUS = array(
		self::PENDING,
		self::PUBLISHED,
		self::REJECTED,
	);

	/**
	 * Status
	 *
	 * @var string status.
	 */
	private $status;

	/**
	 * Status constructor.
	 *
	 * @param string $status status.
	 */
	private function __construct( string $status ) {
		$this->status = $status;
	}

	/**
	 * Creates From status.
	 *
	 * @param string $status status.
	 *
	 * @return Status
	 * @throws StatusNotFound Status not found.
	 */
	public static function from_status( string $status ): Status {
		if ( ! in_array( $status, self::STATUS, true ) ) {
			throw new StatusNotFound( $status );
		}

		return new static( $status );
	}

	/**
	 * Creates from new
	 *
	 * @return Status
	 */
	public static function new(): Status {
		return new static( self::PENDING );
	}

	/**
	 * Checks if its pending or rejected.
	 *
	 * @return bool
	 */
	public function is_pending_or_rejected(): bool {
		return self::PENDING === $this->status || self::REJECTED === $this->status;
	}

	/**
	 * Checks if its published.
	 *
	 * @return bool
	 */
	public function is_published(): bool {
		return self::PUBLISHED === $this->status;
	}

	/**
	 * Compares status.
	 *
	 * @param Status $status status.
	 *
	 * @return bool
	 */
	public function equals( Status $status ): bool {
		return $this->status === $status->get_status();
	}

	/**
	 * Gets status.
	 *
	 * @return string
	 */
	public function get_status(): string {
		return $this->status;
	}
}
