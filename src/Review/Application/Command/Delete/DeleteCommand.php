<?php

declare( strict_types=1 );

namespace BetterReview\Review\Application\Command\Delete;

/**
 * Class DeleteCommand
 *
 * @package BetterReview\Review\Application\Command\Delete
 */
final class DeleteCommand {
	/**
	 * Gets the uuid.
	 *
	 * @var string
	 */
	private $uuid;

	/**
	 * DeleteCommand constructor.
	 *
	 * @param string $uuid uuid.
	 */
	public function __construct( string $uuid ) {
		$this->uuid = $uuid;
	}

	/**
	 * Gets the uuid.
	 *
	 * @return string
	 */
	public function get_uuid(): string {
		return $this->uuid;
	}
}
