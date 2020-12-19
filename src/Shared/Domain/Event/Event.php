<?php

declare( strict_types=1 );

namespace BetterReview\Shared\Domain\Event;

/**
 * Interface Event
 *
 * @package BetterReview\Shared\Domain\Event
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
