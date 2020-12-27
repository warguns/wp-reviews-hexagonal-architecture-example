<?php
/**
 * ReviewRepository
 *
 * @package Review
 */

declare( strict_types=1 );

namespace HexagonalReviews\Review\Domain\Repository;

use HexagonalReviews\Review\Domain\Entity\Review;
use HexagonalReviews\Review\Domain\Exception\IncorrectStars;
use HexagonalReviews\Review\Domain\Exception\ReviewNotFound;
use HexagonalReviews\Review\Domain\Exception\StatusNotFound;
use HexagonalReviews\Review\Domain\ValueObject\ReviewCollection;
use HexagonalReviews\Shared\Domain\ValueObject\ProductId;
use Ramsey\Uuid\UuidInterface;

/**
 * Interface ReviewRepository
 *
 * @package HexagonalReviews\Review\Domain\Repository
 */
interface ReviewRepository {
	public const LIMIT  = 100;
	public const OFFSET = 0;

	/**
	 * Get.
	 *
	 * @param UuidInterface $review_uuid review uuid.
	 *
	 * @return Review
	 * @throws ReviewNotFound Not Found.
	 */
	public function get( UuidInterface $review_uuid ): Review;

	/**
	 * Insert.
	 *
	 * @param Review $review review.
	 *
	 * @return bool
	 */
	public function insert( Review $review ): bool;

	/**
	 * Update.
	 *
	 * @param Review $review update.
	 *
	 * @return bool
	 */
	public function update( Review $review ): bool;

	/**
	 * Delete.
	 *
	 * @param UuidInterface $review_uuid uuid.
	 *
	 * @return bool
	 */
	public function delete( UuidInterface $review_uuid ): bool;

	/**
	 * Finds by post.
	 *
	 * @param ProductId $product_id product.
	 * @param int       $limit limit.
	 * @param int       $offset offset.
	 *
	 * @return ReviewCollection
	 */
	public function find_by_post( ProductId $product_id, int $limit = self::LIMIT, int $offset = self::OFFSET ): ReviewCollection;

	/**
	 * All
	 *
	 * @param int         $limit limit.
	 * @param int         $offset offset.
	 * @param array       $orderby orderby.
	 * @param string|null $search search.
	 *
	 * @return ReviewCollection
	 */
	public function all( int $limit = self::LIMIT, int $offset = self::OFFSET, array $orderby = array(), string $search = null ): ReviewCollection;

	/**
	 * Counts all.
	 *
	 * @param string|null $search search.
	 *
	 * @return int
	 */
	public function count_all( string $search = null ): int;
}
