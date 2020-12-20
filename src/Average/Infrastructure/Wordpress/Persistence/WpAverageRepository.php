<?php
/**
 * WpAverageRepository
 *
 * @package Average
 */

declare( strict_types=1 );

namespace BetterReview\Average\Infrastructure\Wordpress\Persistence;

use BetterReview\Average\Domain\Entity\Average;
use BetterReview\Average\Domain\Repository\AverageRepository;
use BetterReview\Shared\Domain\ValueObject\ProductId;

/**
 * Class WpAverageRepository
 *
 * @package BetterReview\Average\Infrastructure\Wordpress\Persistence
 */
final class WpAverageRepository implements AverageRepository {

	/**
	 * Prefix
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * WpAverageRepository constructor.
	 *
	 * @param string $prefix prefix.
	 */
	public function __construct( string $prefix ) {
		$this->prefix = $prefix;
	}

	/**
	 * Find Product id.
	 *
	 * @param ProductId $product_id product id.
	 *
	 * @return Average|null
	 */
	public function find( ProductId $product_id ): ?Average {
		global $wpdb;

		$result = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}better_review_average WHERE post_id = %s",
				$product_id->get_id()
			),
			ARRAY_A
		);

		if ( null === $result ) {
			return null;
		}
		return Average::from_result( $result );
	}

	/**
	 * Insert Average.
	 *
	 * @param Average $average average.
	 *
	 * @return bool
	 */
	public function insert( Average $average ): bool {
		global $wpdb;
		return (bool) $wpdb->insert( $this->prefix . 'better_review_average', $average->to_array() );
	}

	/**
	 * Update Average.
	 *
	 * @param Average $average average.
	 *
	 * @return bool
	 */
	public function update( Average $average ): bool {
		global $wpdb;
		return (bool) $wpdb->update( $this->prefix . 'better_review_average', $average->to_array(), array( 'post_id' => $average->get_product_id()->get_id() ) );
	}
}
