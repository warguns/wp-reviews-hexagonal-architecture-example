<?php
/**
 * Average
 *
 * @package Average
 */

declare( strict_types=1 );

namespace HexagonalReviews\Average\Domain\Entity;

use HexagonalReviews\Shared\Domain\ValueObject\ProductId;

/**
 * Class Average
 *
 * @package HexagonalReviews\Average\Domain\Entity
 */
final class Average {

	/**
	 * Product Id.
	 *
	 * @var ProductId
	 */
	private $product_id;

	/**
	 * Review count.
	 *
	 * @var int
	 */
	private $review_count;

	/**
	 * Positives
	 *
	 * @var float
	 */
	private $positives;

	/**
	 * Average constructor.
	 *
	 * @param ProductId $product_id product id.
	 * @param int       $review_count review count.
	 * @param float     $positives total positives.
	 */
	public function __construct( ProductId $product_id, int $review_count, float $positives ) {
		$this->product_id   = $product_id;
		$this->review_count = $review_count;
		$this->positives    = $positives;
	}

	/**
	 * Creates From result
	 *
	 * @param array $result result.
	 *
	 * @return static
	 */
	public static function from_result( array $result ): self {
		return new static(
			ProductId::from_int( (int) $result['post_id'] ),
			(int) $result['review_count'],
			(float) $result['positives']
		);
	}

	/**
	 * Converts to array
	 *
	 * @return array
	 */
	public function to_array(): array {
		return array(
			'post_id'      => $this->get_product_id()->get_id(),
			'review_count' => $this->get_review_count(),
			'positives'    => $this->get_positives(),
		);
	}

	/**
	 * Getter
	 *
	 * @return ProductId
	 */
	public function get_product_id(): ProductId {
		return $this->product_id;
	}

	/**
	 * Get Review Count
	 *
	 * @return int
	 */
	public function get_review_count(): int {
		return $this->review_count;
	}

	/**
	 * Get Total Review
	 *
	 * @return float
	 */
	public function get_positives(): float {
		return $this->positives;
	}
}
