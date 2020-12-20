<?php
/**
 * Container
 *
 * @package Shared
 */

declare( strict_types=1 );

namespace BetterReview\Shared\Infrastructure\DependencyInjection;

use BetterReview\Average\Application\Event\OnReviewAddedRecalculateAverage;
use BetterReview\Average\Application\Event\OnReviewDeletedRecalculateAverage;
use BetterReview\Average\Application\Event\OnReviewUpdatedRecalculateAverage;
use BetterReview\Average\Application\Query\GetAverage\GetAverageHandler;
use BetterReview\Average\Domain\Service\AverageCalculator;
use BetterReview\Average\Infrastructure\Wordpress\Persistence\WpAverageRepository;
use BetterReview\Review\Application\Command\Create\CreateHandler;
use BetterReview\Review\Application\Command\Delete\DeleteHandler;
use BetterReview\Review\Application\Command\Update\UpdateHandler;
use BetterReview\Review\Application\Query\All\ListHandler;
use BetterReview\Review\Application\Query\Get\GetHandler;
use BetterReview\Review\Application\Query\GetByPost\GetByPostHandler;
use BetterReview\Review\Domain\Event\ReviewAdded;
use BetterReview\Review\Domain\Event\ReviewDeleted;
use BetterReview\Review\Domain\Event\ReviewUpdated;
use BetterReview\Review\Infrastructure\Wordpress\Persistence\WpReviewRepository;
use BetterReview\Shared\Infrastructure\DependencyInjection\Exception\DependencyNotFound;
use BetterReview\Shared\Infrastructure\EventDispatcher\EventDispatcher;
use Psr\Container\ContainerInterface;

/**
 * Class Container
 *
 * @package BetterReview\Shared\Infrastructure\DependencyInjection
 */
final class Container implements ContainerInterface {

	/**
	 * The Container Variable
	 *
	 * @var array the container.
	 */
	private $container;

	/**
	 * Container constructor.
	 */
	public function __construct() {
		global $wpdb;
		$review_repository  = new WpReviewRepository( $wpdb->prefix );
		$average_repository = new WpAverageRepository( $wpdb->prefix );
		$event_dispatcher   = new EventDispatcher(
			array(
				ReviewAdded::class   => array( OnReviewAddedRecalculateAverage::class ),
				ReviewUpdated::class => array( OnReviewUpdatedRecalculateAverage::class ),
				ReviewDeleted::class => array( OnReviewDeletedRecalculateAverage::class ),
			)
		);

		$create_handler = new CreateHandler( $review_repository, $event_dispatcher );
		$update_handler = new UpdateHandler( $review_repository, $event_dispatcher );
		$delete_handler = new DeleteHandler( $review_repository, $event_dispatcher );

		$list_handler    = new ListHandler( $review_repository );
		$by_post_handler = new GetByPostHandler( $review_repository );
		$get_handler     = new GetHandler( $review_repository );

		$calculator          = new AverageCalculator();
		$get_average_handler = new GetAverageHandler( $average_repository, $calculator );

		$on_review_added_recalculate_average   = new OnReviewAddedRecalculateAverage( $average_repository );
		$on_review_updated_recalculate_average = new OnReviewUpdatedRecalculateAverage( $average_repository );
		$on_review_deleted_recalculate_average = new OnReviewDeletedRecalculateAverage( $average_repository );

		$this->container = array(
			CreateHandler::class                     => $create_handler,
			UpdateHandler::class                     => $update_handler,
			DeleteHandler::class                     => $delete_handler,
			ListHandler::class                       => $list_handler,
			GetHandler::class                        => $get_handler,
			GetByPostHandler::class                  => $by_post_handler,
			OnReviewAddedRecalculateAverage::class   => $on_review_added_recalculate_average,
			OnReviewUpdatedRecalculateAverage::class => $on_review_updated_recalculate_average,
			OnReviewDeletedRecalculateAverage::class => $on_review_deleted_recalculate_average,
			GetAverageHandler::class                 => $get_average_handler,
		);
	}

	/**
	 * Dependency Getter
	 *
	 * @param string $id dependency class name.
	 *
	 * @return mixed
	 * @throws DependencyNotFound When The Dependency is not found.
	 */
	public function get( $id ) {

		if ( ! $this->has( $id ) ) {
			throw new DependencyNotFound( $id );
		}

		return $this->container[ $id ];
	}

	/**
	 * Container Getter
	 *
	 * @param string $id id.
	 *
	 * @return bool
	 */
	public function has( $id ): bool {
		return array_key_exists( $id, $this->container );
	}
}
