<?php

declare( strict_types=1 );

namespace BetterReview\Review\Application\Query\Get;

/**
 * Class GetQuery
 *
 * @package BetterReview\Review\Application\Query\Get
 */
final class GetQuery {

	/**
	 * Uuid param
	 *
	 * @var string $uuid uuid.
	 */
	private $uuid;

	/**
	 * GetQuery constructor.
	 *
	 * @param string $uuid uuid.
	 */
	public function __construct( string $uuid ) {
		$this->uuid = $uuid;
	}

	/**
	 * Gets the uuid
	 *
	 * @return string
	 */
	public function get_uuid(): string {
		return $this->uuid;
	}
}
