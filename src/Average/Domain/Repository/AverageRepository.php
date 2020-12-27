<?php
/**
 * AverageRepository
 *
 * @package Average
 */

declare( strict_types=1 );

namespace HexagonalReviews\Average\Domain\Repository;

use HexagonalReviews\Average\Domain\Entity\Average;
use HexagonalReviews\Shared\Domain\ValueObject\ProductId;

/**
 * Interface AverageRepository
 *
 * @package HexagonalReviews\Average\Domain\Repository
 */
interface AverageRepository {

	/**
	 * Finds product Average.
	 *
	 * @param ProductId $product_id product id.
	 *
	 * @return Average|null
	 */
	public function find( ProductId $product_id ): ?Average;

	/**
	 * Insert Average.
	 *
	 * @param Average $average average.
	 *
	 * @return bool
	 */
	public function insert( Average $average ): bool;

	/**
	 * Update Average.
	 *
	 * @param Average $average average.
	 *
	 * @return bool
	 */
	public function update( Average $average ): bool;
}
