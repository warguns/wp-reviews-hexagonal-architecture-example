<?php
/**
 * GetAverageQuery
 *
 * @package Average
 */

declare( strict_types=1 );

namespace HexagonalReviews\Average\Application\Query\GetAverage;

/**
 * Class GetAverageQuery
 *
 * @package HexagonalReviews\Average\Application\Query\GetAverage
 */
final class GetAverageQuery {
	/**
	 * Product id.
	 *
	 * @var int
	 */
	private $product_id;

	/**
	 * GetAverageQuery constructor.
	 *
	 * @param int $product_id product_id.
	 */
	public function __construct( int $product_id ) {
		$this->product_id = $product_id;
	}

	/**
	 * Gets the product id.
	 *
	 * @return int
	 */
	public function get_product_id(): int {
		return $this->product_id;
	}
}
