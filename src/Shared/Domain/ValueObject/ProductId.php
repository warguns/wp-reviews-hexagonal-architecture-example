<?php
/**
 * ProductId
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Domain\ValueObject;

/**
 * Class ProductId
 *
 * @package BetterReview\Shared\Domain\ValueObject
 */
final class ProductId {
	/**
	 * Id.
	 *
	 * @var int
	 */
	private $id;

	/**
	 * ProductId constructor.
	 *
	 * @param int $id id.
	 */
	public function __construct( int $id ) {
		$this->id = $id;
	}

	/**
	 * Creates from id.
	 *
	 * @param int $id id.
	 *
	 * @return static
	 */
	public static function from_int( int $id ): self {
		return new static( $id );
	}

	/**
	 * Gets the id.
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->id;
	}
}
