<?php

declare( strict_types=1 );

namespace BetterReview\Review\Infrastructure\Wordpress\Persistence;

use BetterReview\Review\Domain\Entity\Review;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;
use BetterReview\Shared\Domain\ValueObject\ProductId;
use Ramsey\Uuid\UuidInterface;

/**
 * Class WpReviewRepository
 *
 * @package BetterReview\Review\Infrastructure\Wordpress\Persistence
 */
final class WpReviewRepository implements ReviewRepository {

	/**
	 * Prefix.
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * WpReviewRepository constructor.
	 *
	 * @param string $prefix prefix.
	 */
	public function __construct( string $prefix ) {
		$this->prefix = $prefix;
	}

	/**
	 * Get.
	 *
	 * @param UuidInterface $review_uuid review uuid.
	 *
	 * @return Review
	 * @throws ReviewNotFound Not Found.
	 * @throws IncorrectStars IncorrectStars.
	 * @throws StatusNotFound StatusNotFound.
	 */
	public function get( UuidInterface $review_uuid ): Review {
		global $wpdb;
		$table  = $this->prefix . 'better_review';
		$result = $wpdb->get_row(
			$wpdb->prepare(
				"
					SELECT * FROM {$table} WHERE uuid = %s
				",
				array(
					$review_uuid->toString(),
				)
			),
			ARRAY_A
		);

		if ( null === $result ) {
			throw new ReviewNotFound( $review_uuid );
		}

		return Review::from_result( $result );
	}

	/**
	 * Insert
	 *
	 * @param Review $review review.
	 *
	 * @return bool
	 */
	public function insert( Review $review ): bool {
		global $wpdb;
		return (bool) $wpdb->insert( $this->prefix . 'better_review', $review->to_array() );
	}

	/**
	 * Update
	 *
	 * @param Review $review review.
	 *
	 * @return bool
	 */
	public function update( Review $review ): bool {
		global $wpdb;
		return (bool) $wpdb->update( $this->prefix . 'better_review', $review->to_array(), array( 'uuid' => $review->get_uuid()->toString() ) );
	}

	/**
	 * Delete
	 *
	 * @param UuidInterface $review_uuid delete.
	 *
	 * @return bool
	 */
	public function delete( UuidInterface $review_uuid ): bool {
		global $wpdb;
		return (bool) $wpdb->delete( $this->prefix . 'better_review', array( 'uuid' => $review_uuid->toString() ) );
	}

	/**
	 * Finds By post.
	 *
	 * @param ProductId $product_id Product.
	 * @param int       $limit Limit.
	 * @param int       $offset Offset.
	 *
	 * @return ReviewCollection
	 */
	public function find_by_post( ProductId $product_id, int $limit = self::LIMIT, int $offset = self::OFFSET ): ReviewCollection {
		global $wpdb;
		$sql = 'SELECT * FROM ' . $this->prefix . 'better_review WHERE status = "published" and post_id = ' . $product_id->get_id();

		if ( $limit > 0 ) {
			$sql .= ' LIMIT ' . esc_sql( $limit );
			if ( $offset > 0 ) {
				$sql .= ' OFFSET ' . esc_sql( $offset );
			}
		}

		$sql    .= ' ORDER BY created_at DESC';
		$results = $wpdb->get_results( $sql, ARRAY_A );

		return ReviewCollection::from_results( $results );
	}

	/**
	 * Count by post
	 *
	 * @param ProductId $product_id Post.
	 *
	 * @return int
	 */
	public function count_by_post( ProductId $product_id ): int {
		global $wpdb;

		$count = $wpdb->get_results( 'SELECT COUNT(*) as counter  FROM ' . $this->prefix . 'better_review WHERE post_id = ' . $product_id->get_id() . ' ORDER BY created_at DESC', ARRAY_A );

		return $count['counter'];
	}

	/**
	 * Get All reviews
	 *
	 * @param int         $limit limit.
	 * @param int         $offset offset.
	 * @param array       $orderby orderby.
	 * @param string|null $search search.
	 *
	 * @return ReviewCollection
	 */
	public function all( int $limit = self::LIMIT, int $offset = self::OFFSET, array $orderby = array(), string $search = null ): ReviewCollection {
		global $wpdb;
		$sql = 'SELECT * FROM ' . $this->prefix . 'better_review r INNER JOIN ' . $this->prefix . 'posts p on r.post_id = p.id ';

		if ( null !== $search ) {
			$sql .= 'WHERE title LIKE "%' . esc_sql( $search ) . '%" OR content LIKE "%' . esc_sql( $search ) . '%" OR status LIKE "%' . esc_sql( $search ) . '%" ';
		}

		if ( ! empty( $orderby ) ) {
			$sql   .= 'ORDER BY ';
			$orders = array();
			foreach ( $orderby as $field => $sort ) {
				$orders[] = esc_sql( $field ) . ' ' . esc_sql( $sort );
			}
			$sql .= implode( ',', $orders );
		}

		if ( $limit > 0 ) {
			$sql .= ' LIMIT ' . esc_sql( $limit );
			if ( $offset > 0 ) {
				$sql .= ' OFFSET ' . esc_sql( $offset );
			}
		}

		return ReviewCollection::from_results( $wpdb->get_results( $sql, ARRAY_A ) );
	}

	/**
	 * Count all.
	 *
	 * @param string|null $search search.
	 *
	 * @return int
	 */
	public function count_all( string $search = null ): int {
		global $wpdb;
		$sql = 'SELECT COUNT(*) as counter FROM ' . $this->prefix . 'better_review ';

		if ( null !== $search ) {
			$sql .= 'WHERE title LIKE "%' . esc_sql( $search ) . '%" OR content LIKE "%' . esc_sql( $search ) . '%" OR status LIKE "%' . esc_sql( $search ) . '%" ';
		}

		$count = $wpdb->get_row( $sql, ARRAY_A );

		return (int) $count['counter'];
	}
}
