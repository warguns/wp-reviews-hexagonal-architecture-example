<?php
/**
 * GetReviewsBlockController
 *
 * @package UI
 */

declare( strict_types=1 );

namespace HexagonalReviews\UI\Wordpress\Front;

use HexagonalReviews\Average\Application\Query\GetAverage\GetAverageHandler;
use HexagonalReviews\Average\Application\Query\GetAverage\GetAverageQuery;
use HexagonalReviews\Average\Domain\DTO\ReviewStats;
use HexagonalReviews\Review\Application\Command\Create\CreateCommand;
use HexagonalReviews\Review\Application\Command\Create\CreateHandler;
use HexagonalReviews\Review\Application\Query\GetByPost\GetByPostHandler;
use HexagonalReviews\Review\Application\Query\GetByPost\GetByPostQuery;
use HexagonalReviews\Review\Domain\Exception\IncorrectStars;
use HexagonalReviews\Review\Domain\Repository\ReviewRepository;
use HexagonalReviews\Review\Domain\ValueObject\ReviewCollection;
use HexagonalReviews\Shared\Infrastructure\DependencyInjection\Container;
use WP_Post;

/**
 * Class GetReviewsBlockController
 *
 * @package HexagonalReviews\UI\Wordpress\Front
 */
class GetReviewsBlockController {
	/**
	 * By Post Handler.
	 *
	 * @var GetByPostHandler
	 */
	private $get_by_post_handler;

	/**
	 * Average Handler.
	 *
	 * @var GetAverageHandler
	 */
	private $get_average_handler;

	/**
	 * Create handler.
	 *
	 * @var CreateHandler
	 */
	private $create_handler;

	/**
	 * GetReviewsBlockController constructor.
	 */
	public function __construct() {
		$container                 = new Container();
		$this->get_by_post_handler = $container->get( GetByPostHandler::class );
		$this->get_average_handler = $container->get( GetAverageHandler::class );
		$this->create_handler      = $container->get( CreateHandler::class );
	}

	/**
	 * Runs
	 */
	public function run(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_shortcode( 'hexagonal-reviews', array( $this, 'load' ) );
	}

	/**
	 * Load Scripts
	 */
	public function load_scripts(): void {
		wp_enqueue_style( 'hexagonal-reviews-stars', plugins_url( '/assets/stars.css', __FILE__ ), array(), '20201230', 'all' );
		wp_enqueue_style( 'hexagonal-reviews-style', plugins_url( '/assets/style.css', __FILE__ ), array(), '20210119', 'all' );
	}

	/**
	 * Loads the controller.
	 *
	 * @param mixed $attr attributes.
	 *
	 * @return false|string
	 * @throws IncorrectStars IncorrectStars.
	 */
	public function load( $attr ): string {

		$params = shortcode_atts(
			array(
				'post_id'         => 0,
				'limit'           => ReviewRepository::LIMIT,
				'offset'          => 0,
				'type'            => 'Product',
				'form_visible'    => true,
				'avg_visible'     => true,
				'reviews_visible' => true,
			),
			$attr
		);

		if ( isset( $_POST['addreview'] ) && ! wp_verify_nonce( sanitize_key( $_POST['addreview'] ), 'addreview' ) ) {
			die( 'Go get a life script kiddies' );
		}

		$submitted = false;
		if ( isset( $_POST['submit-opinion'], $_POST['author'], $_POST['title'], $_POST['content'], $_POST['email'], $_POST['rating'] ) ) {
			$this->create_handler->run(
				new CreateCommand(
					(int) sanitize_text_field( wp_unslash( $params['post_id'] ) ),
					sanitize_text_field( wp_unslash( $_POST['author'] ) ),
					sanitize_text_field( wp_unslash( $_POST['title'] ) ),
					sanitize_text_field( wp_unslash( $_POST['content'] ) ),
					sanitize_email( wp_unslash( $_POST['email'] ) ),
					(float) sanitize_text_field( wp_unslash( $_POST['rating'] ) )
				)
			);
			$submitted = true;
		}

		$review_stats = $this->get_average_handler->run(
			new GetAverageQuery(
				(int) sanitize_text_field( wp_unslash( $params['post_id'] ) )
			)
		);

		$review_collection = $this->get_by_post_handler->run(
			new GetByPostQuery(
				(int) sanitize_text_field( wp_unslash( $params['post_id'] ) ),
				(int) sanitize_text_field( wp_unslash( $params['limit'] ) ),
				(int) sanitize_text_field( wp_unslash( $params['offset'] ) )
			)
		);

		$review_type     = (string) sanitize_text_field( wp_unslash( $params['type'] ) );
		$form_visible    = (bool) sanitize_text_field( wp_unslash( $params['form_visible'] ) );
		$avg_visible     = (bool) sanitize_text_field( wp_unslash( $params['avg_visible'] ) );
		$reviews_visible = (bool) sanitize_text_field( wp_unslash( $params['reviews_visible'] ) );

		$post = WP_Post::get_instance( (int) sanitize_text_field( wp_unslash( $params['post_id'] ) ) );

		return $this->render( $review_collection, $review_stats, $post, $submitted, $review_type, $form_visible, $avg_visible, $reviews_visible );
	}

	/**
	 * Renders template
	 *
	 * @param ReviewCollection $review_collection review Collection.
	 * @param ReviewStats      $review_stats review stats.
	 * @param WP_Post          $post post.
	 * @param bool             $submitted check if is the form submitted.
	 * @param string           $review_type review type.
	 * @param bool             $form_visible form visibility.
	 * @param bool             $avg_visible average visibility.
	 * @param bool             $reviews_visible reviews visibility.
	 *
	 * @return false|string
	 */
	private function render( ReviewCollection $review_collection, ReviewStats $review_stats, WP_Post $post, bool $submitted, string $review_type, bool $form_visible, bool $avg_visible, bool $reviews_visible ) {
		ob_start();
		include 'templates/GetReviewsBlock.php';

		return ob_get_clean();
	}
}
