<?php

declare( strict_types=1 );

namespace BetterReview\UI\Wordpress\Admin;

use BetterReview\Review\Application\Command\Delete\DeleteCommand;
use BetterReview\Review\Application\Command\Delete\DeleteHandler;
use BetterReview\Review\Application\Query\All\ListHandler;
use BetterReview\Review\Application\Query\All\ListQuery;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Review\Domain\Repository\ReviewRepository;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;
use BetterReview\Shared\Infrastructure\Wordpress\ReviewsAdminTable;
use WP_Post;
use WP_Query;
use function add_menu_page;

/**
 * Class ListReviewsController
 *
 * @package BetterReview\UI\Wordpress\Admin
 */
class ListReviewsController {

	/**
	 * Handler
	 *
	 * @var ListHandler
	 */
	private $list_handler;

	/**
	 * Handler
	 *
	 * @var DeleteHandler
	 */
	private $delete_handler;

	/**
	 * ListReviewsController constructor.
	 */
	public function __construct() {
		$this->list_handler   = Container::resolve( ListHandler::class );
		$this->delete_handler = Container::resolve( DeleteHandler::class );
	}

	/**
	 * Run
	 */
	public function run(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_style' ) );
		add_menu_page( 'Reviews', 'Reviews', 'manage_options', 'reviews', array( $this, 'load' ), 'dashicons-chart-pie', 999 );
	}

	/**
	 * Admin Style
	 */
	public function admin_style(): void {
		wp_enqueue_style( 'starability', plugins_url( '/../Front/assets/stars.css', __FILE__ ), array(), '20201212', 'all' );
		wp_enqueue_style( 'better-review-style', plugins_url( '/../Front/assets/style.css', __FILE__ ), array(), '20201212', 'all' );
	}

	/**
	 * Load
	 */
	public function load(): void {

		$this->bulk_delete();

		$reviews_response = $this->list_handler->run(
			new ListQuery(
				$_REQUEST['s'] ?? null,
				ReviewRepository::LIMIT,
				( ( (int) isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : 1 ) - 1 ) * ReviewRepository::LIMIT,
				$_REQUEST['orderby'] ?? null,
				$_REQUEST['order'] ?? null
			)
		);

		$query      = new WP_Query( array( 'post__in' => $reviews_response->get_review_collection()->get_product_ids() ) );
		$post_names = array();
		/**
		 * The post
		 *
		 * @var WP_Post $post post.
		 */
		foreach ( $query->posts as $post ) {
			$post_names[ $post->ID ] = $post->post_name;
		}

		$table = new ReviewsAdminTable(
			$reviews_response->get_review_collection()->to_array(),
			$reviews_response->get_totals(),
			$post_names,
			array(
				'singular' => __( 'Review', 'sp' ),
				'plural'   => __( 'Reviews', 'sp' ),
				'ajax'     => false,
			)
		);

		$this->render( $table );
	}

	/**
	 * Bulk Delete
	 *
	 * @throws IncorrectStars IncorrectStars.
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws StatusNotFound StatusNotFound.
	 */
	protected function bulk_delete(): void {
		if ( ( isset( $_POST['action'] ) && 'bulk-delete' === $_POST['action'] ) ) {
			$this->verify_nonce();

			$delete_uuids = esc_sql( $_REQUEST['bulk-delete'] );

			foreach ( $delete_uuids as $uuid ) {
				$this->delete_handler->run( new DeleteCommand( $uuid ) );
			}
		}
	}

	/**
	 * Verify nonce
	 */
	private function verify_nonce(): void {
		if ( ! check_admin_referer( 'listreviews', 'listreviews' ) ) {
			die( 'Go get a life script kiddies' );
		}
	}

	/**
	 * Render
	 *
	 * @param ReviewsAdminTable $table table.
	 */
	private function render( ReviewsAdminTable $table ): void {
		include 'templates/ListReviews.php';
	}
}
