<?php
/**
 * GetReviewsBlockController
 *
 * @package UI
 */

declare( strict_types=1 );

namespace BetterReview\UI\Wordpress\Front;

use BetterReview\Average\Application\Query\GetAverage\GetAverageHandler;
use BetterReview\Average\Application\Query\GetAverage\GetAverageQuery;
use BetterReview\Average\Domain\DTO\ReviewStats;
use BetterReview\Review\Application\Command\Create\CreateCommand;
use BetterReview\Review\Application\Command\Create\CreateHandler;
use BetterReview\Review\Application\Query\GetByPost\GetByPostHandler;
use BetterReview\Review\Application\Query\GetByPost\GetByPostQuery;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\ValueObject\ReviewCollection;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;
use WP_Post;

/**
 * Class GetReviewsBlockController
 *
 * @package BetterReview\UI\Wordpress\Front
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
		$this->get_by_post_handler = Container::resolve( GetByPostHandler::class );
		$this->get_average_handler = Container::resolve( GetAverageHandler::class );
		$this->create_handler      = Container::resolve( CreateHandler::class );
	}

	/**
	 * Runs
	 */
	public function run(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_shortcode( 'better-reviews', array( $this, 'load' ) );
	}

	/**
	 * Load Scripts
	 */
	public function load_scripts(): void {
		wp_enqueue_script( 'luxon', plugins_url( '/assets/luxon.js', __FILE__ ), array(), '20201213', true );
		wp_enqueue_style( 'better-review-stars', plugins_url( '/assets/stars.css', __FILE__ ), array(), '20201213', 'all' );
		wp_enqueue_style( 'better-review-style', plugins_url( '/assets/style.css', __FILE__ ), array(), '20201213', 'all' );
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
				'post_id' => 0,
				'limit'   => 0,
				'offset'  => 0,
			),
			$attr
		);

		if ( isset( $_POST['submit-opinion'] ) && $_POST['submit-opinion'] ) {
			$this->verify_nonce();

			$this->create_handler->run(
				new CreateCommand(
					(int) esc_attr( $params['post_id'] ),
					sanitize_text_field( wp_unslash( $_POST['author'] ) ),
					sanitize_text_field( $_POST['title'] ),
					esc_textarea( $_POST['content'] ),
					sanitize_email( $_POST['email'] ),
					(float) sanitize_text_field( $_POST['rating'] )
				)
			);
		}

		$review_stats = $this->get_average_handler->run(
			new GetAverageQuery(
				(int) esc_attr( $params['post_id'] )
			)
		);

		$review_collection = $this->get_by_post_handler->run(
			new GetByPostQuery(
				(int) esc_attr( $params['post_id'] ),
				(int) esc_attr( $params['limit'] ),
				(int) esc_attr( $params['offset'] )
			)
		);

		$post = WP_Post::get_instance( (int) esc_attr( $params['post_id'] ) );

		return $this->render( $review_collection, $review_stats, $post );
	}

	/**
	 * Verify Once
	 */
	private function verify_nonce(): void {
		if ( ! wp_verify_nonce( wp_unslash( $_POST['addreview'] ), 'addreview' ) ) {
			die( 'Go get a life script kiddies' );
		}
	}

	/**
	 * Renders template
	 *
	 * @param ReviewCollection $review_collection review Collection.
	 * @param ReviewStats      $review_stats review stats.
	 * @param WP_Post          $post post.
	 *
	 * @return false|string
	 */
	private function render( ReviewCollection $review_collection, ReviewStats $review_stats, WP_Post $post ) {
		ob_start();
		include 'templates/GetReviewsBlock.php';

		return ob_get_clean();
	}
}
