<?php
/**
 * ReviewAdded
 *
 * @package Review
 */

declare( strict_types=1 );

namespace BetterReview\Review\Domain\Event;

use BetterReview\Shared\Domain\Event\Event;
use Ramsey\Uuid\UuidInterface;

/**
 * Class ReviewAdded
 *
 * @package BetterReview\Review\Domain\Event
 */
final class ReviewAdded implements Event {

	/**
	 * Correlation uuid.
	 *
	 * @var UuidInterface uuid.
	 */
	private $correlation_uuid;

	/**
	 * Parent uuid.
	 *
	 * @var UuidInterface parent uuid.
	 */
	private $parent_uuid;

	/**
	 * Product id.
	 *
	 * @var int product_id.
	 */
	private $product_id;

	/**
	 * Status string.
	 *
	 * @var string status.
	 */
	private $status;

	/**
	 * Stars.
	 *
	 * @var float stars.
	 */
	private $stars;

	/**
	 * ReviewAdded constructor.
	 *
	 * @param UuidInterface $correlation_uuid correlation uuid.
	 * @param UuidInterface $parent_uuid parent uuid.
	 * @param int           $product_id product id.
	 * @param string        $status status string.
	 * @param float         $stars stars.
	 */
	public function __construct( UuidInterface $correlation_uuid, UuidInterface $parent_uuid, int $product_id, string $status, float $stars ) {
		$this->correlation_uuid = $correlation_uuid;
		$this->parent_uuid      = $parent_uuid;
		$this->product_id       = $product_id;
		$this->status           = $status;
		$this->stars            = $stars;
	}

	/**
	 * Gets parent id
	 *
	 * @return string
	 */
	public function get_parent_uuid(): string {
		return $this->parent_uuid->toString();
	}

	/**
	 * Get Correlation id
	 *
	 * @return string
	 */
	public function get_correlation_uuid(): string {
		return $this->correlation_uuid->toString();
	}

	/**
	 * Gets product id.
	 *
	 * @return int
	 */
	public function get_product_id(): int {
		return $this->product_id;
	}

	/**
	 * Gets Status.
	 *
	 * @return string
	 */
	public function get_status(): string {
		return $this->status;
	}

	/**
	 * Get Stars.
	 *
	 * @return float
	 */
	public function get_stars(): float {
		return $this->stars;
	}
}
