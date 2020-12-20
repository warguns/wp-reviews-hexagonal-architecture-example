<?php
/**
 * ReviewCollection
 *
 * @package Review
 */

declare( strict_types=1 );

namespace BetterReview\Review\Domain\ValueObject;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use Iterator;

/**
 * Class ReviewCollection
 *
 * @package BetterReview\Review\Domain\ValueObject
 */
final class ReviewCollection implements Iterator {
	/**
	 * Collection.
	 *
	 * @var array
	 */
	private $collection;

	/**
	 * ReviewCollection constructor.
	 *
	 * @param array $collection collection.
	 */
	private function __construct( array $collection = array() ) {
		$this->collection = $collection;
	}

	/**
	 * Create Collection from results.
	 *
	 * @param array $results results.
	 *
	 * @return static
	 *
	 * @throws IncorrectStars Incorrect stars.
	 * @throws StatusNotFound Status not found.
	 */
	public static function from_results( array $results ): self {
		$review_array = array();
		foreach ( $results as $result ) {
			$review_array[] = Review::from_result( $result );
		}

		return new ReviewCollection( $review_array );
	}

	/**
	 * Current
	 *
	 * @return mixed
	 */
	public function current() {
		return current( $this->collection );
	}

	/**
	 * Next
	 *
	 * @return mixed|void
	 */
	public function next() {
		return next( $this->collection );
	}

	/**
	 * Key
	 *
	 * @return bool|float|int|string|null
	 */
	public function key() {
		return key( $this->collection );
	}

	/**
	 * Valid
	 *
	 * @return bool|mixed
	 */
	public function valid() {
		return current( $this->collection );
	}

	/**
	 * Rewind
	 *
	 * @return mixed|void
	 */
	public function rewind() {
		return reset( $this->collection );
	}

	/**
	 * Converts to array
	 *
	 * @return array
	 */
	public function to_array(): array {
		$collection = array();
		/**
		 * Review
		 *
		 * @var Review $review review.
		 */
		foreach ( $this->collection as $review ) {
			$collection[] = $review->to_array();
		}

		return $collection;
	}

	/**
	 * Count
	 *
	 * @return int count.
	 */
	public function count(): int {
		return count( $this->collection );
	}

	/**
	 * Get Posts Ids.
	 *
	 * @return array
	 */
	public function get_product_ids(): array {
		$ids = array();
		/**
		 * Review
		 *
		 * @var Review $review review.
		 */
		foreach ( $this->collection as $review ) {
			$ids[] = $review->get_product_id()->get_id();
		}

		return $ids;
	}
}
