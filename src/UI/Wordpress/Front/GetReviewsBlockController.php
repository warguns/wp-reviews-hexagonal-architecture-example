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
use BetterReview\Review\Domain\Repository\ReviewRepository;
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
		add_shortcode( 'better-reviews', array( $this, 'load' ) );
	}

	/**
	 * Load Scripts
	 */
	public function load_scripts(): void {
		wp_enqueue_script( 'luxon', '//cdn.jsdelivr.net/npm/luxon@1.25.0/build/global/luxon.min.js', array(), '20201213', true );
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
				'limit'   => ReviewRepository::LIMIT,
				'offset'  => 0,
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

		$post = WP_Post::get_instance( (int) sanitize_text_field( wp_unslash( $params['post_id'] ) ) );

		return $this->render( $review_collection, $review_stats, $post, $submitted );
	}

	/**
	 * Renders template
	 *
	 * @param ReviewCollection $review_collection review Collection.
	 * @param ReviewStats      $review_stats review stats.
	 * @param WP_Post          $post post.
	 * @param bool             $submitted check if is the form submitted.
	 *
	 * @return false|string
	 */
	private function render( ReviewCollection $review_collection, ReviewStats $review_stats, WP_Post $post, bool $submitted ) {
		ob_start();
		include 'templates/GetReviewsBlock.php';

		return ob_get_clean();
	}
}
