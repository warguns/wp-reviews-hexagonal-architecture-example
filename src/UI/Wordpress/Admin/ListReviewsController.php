<?php
/**
 * ListReviewsController
 *
 * @package UI
 */

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
	 *
	 * @throws IncorrectStars IncorrectStars.
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws StatusNotFound StatusNotFound.
	 */
	public function load(): void {
		$this->bulk_delete();
		$params           = $this->get_list_params();
		$reviews_response = $this->list_handler->run(
			new ListQuery(
				$params['s'] ?? null,
				ReviewRepository::LIMIT,
				( ( (int) isset( $params['paged'] ) ? $params['paged'] : 1 ) - 1 ) * ReviewRepository::LIMIT,
				$params['orderby'] ?? null,
				$params['order'] ?? null
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
		$params = $this->get_bulk_delete_params();
		if ( ( $params && 'bulk-delete' === $params['action'] ) ) {
			foreach ( $params['bulk-delete'] as $uuid ) {
				$this->delete_handler->run( new DeleteCommand( sanitize_key( $uuid ) ) );
			}
		}
	}

	/**
	 * Get Params
	 *
	 * @return array|null
	 */
	private function get_list_params(): ?array {
		if ( isset( $_REQUEST['listreviews'] ) && check_admin_referer( 'listreviews', 'listreviews' ) && wp_verify_nonce( sanitize_key( $_REQUEST['listreviews'] ), 'listreviews' ) ) {
			$params['s'] = ( isset( $_REQUEST['s'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) : null;
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				$_SERVER['REQUEST_URI'] = add_query_arg( array( 's' => $params['s'] ), sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			}
		}
		$params['paged']   = ( isset( $_REQUEST['paged'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['paged'] ) ) : null;
		$params['orderby'] = ( isset( $_REQUEST['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : null;
		$params['order']   = ( isset( $_REQUEST['order'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : null;

		return $params;
	}

	/**
	 * Get Params
	 *
	 * @return array|null
	 */
	private function get_bulk_delete_params(): ?array {
		if ( isset( $_REQUEST['listreviews'], $_REQUEST['action'], $_REQUEST['bulk-delete'] ) && check_admin_referer( 'listreviews', 'listreviews' ) && wp_verify_nonce( sanitize_key( $_REQUEST['listreviews'] ), 'listreviews' ) ) {
			$params['action'] = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) );
			// Sanitized during deletion.
			$params['bulk-delete'] = wp_unslash( $_REQUEST['bulk-delete'] ); // @codingStandardsIgnoreLine

			return $params;
		}

		return null;
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
