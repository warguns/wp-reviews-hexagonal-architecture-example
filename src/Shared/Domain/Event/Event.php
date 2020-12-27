<?php
/**
 * Event
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace HexagonalReviews\Shared\Domain\Event;

/**
 * Interface Event
 *
 * @package HexagonalReviews\Shared\Domain\Event
 */
interface Event {
	/**
	 * Getter.
	 *
	 * @return string
	 */
	public function get_parent_uuid(): string;

	/**
	 * Getter.
	 *
	 * @return string
	 */
	public function get_correlation_uuid(): string;
}
