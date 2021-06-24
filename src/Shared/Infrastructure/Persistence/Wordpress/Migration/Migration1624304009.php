<?php
/**
 * Migration v1.2.12
 *
 * @package Shared
 */

namespace HexagonalReviews\Shared\Infrastructure\Persistence\Wordpress\Migration;

use HexagonalReviews\Review\Domain\Entity\Review;
use HexagonalReviews\Review\Domain\Event\ReviewAdded;
use HexagonalReviews\Shared\Infrastructure\DependencyInjection\Container;
use HexagonalReviews\Shared\Infrastructure\EventDispatcher\EventDispatcher;
use Ramsey\Uuid\Uuid;

/**
 * Class Migration1624304009
 *
 * @package HexagonalReviews\Shared\Infrastructure\Persistence\Wordpress\Migration
 */
final class Migration1624304009 implements MigrationInterface {

	/**
	 * Container
	 *
	 * @var Container container.
	 */
	private $container;

	/**
	 * Migration1624304009 constructor.
	 */
	public function __construct() {
		$this->container = new Container();
	}


	/**
	 * Gets the version
	 */
	public function get_version(): string {
		return '1.2.12';
	}

	/**
	 * Migration.
	 */
	public function run(): void {
		global $wpdb;

		$wpdb->query( "DROP TABLE `{$wpdb->prefix}hexagonal_review_average`" );

		$wpdb->query(
			"CREATE TABLE `{$wpdb->prefix}hexagonal_review_average` (
					  `post_id` bigint(20) unsigned NOT NULL,
					  `review_count` int(11) DEFAULT '0',
					  `positives` int(11) DEFAULT '0',
					  PRIMARY KEY (`post_id`),
					  CONSTRAINT `post_id` FOREIGN KEY (`post_id`) REFERENCES `wp_posts` (`ID`)
					) ENGINE=InnoDB DEFAULT CHARSET=latin1"
		);

		$counts = $wpdb->get_results(
			"SELECT COUNT(*) as `total` FROM {$wpdb->prefix}hexagonal_review",
			ARRAY_A
		);

		/**
		 * Event dispatcher
		 *
		 * @var EventDispatcher $dispatcher event dispatcher.
		 */
		$dispatcher = $this->container->get( EventDispatcher::class );

		for ( $offset = 0; $offset <= ( $counts['total'] + 100 ); $offset += 100 ) {
			$results = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM `{$wpdb->prefix}hexagonal_review` LIMIT %d OFFSET %d ",
					100,
					$offset
				),
				ARRAY_A
			);

			if ( null !== $results ) {
				foreach ( $results as $result ) {
					$review = Review::from_result( $result );
					$dispatcher->dispatch(
						new ReviewAdded(
							UUid::uuid4(),
							UUid::uuid4(),
							$review->get_product_id()->get_id(),
							$review->get_status()->get_status(),
							$review->get_stars()->get_stars()
						)
					);
				}
			}
		}
	}
}