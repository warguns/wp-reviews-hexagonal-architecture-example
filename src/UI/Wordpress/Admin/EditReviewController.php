<?php
/**
 * EditReviewController
 *
 * @package UI
 */

declare( strict_types=1 );

namespace HexagonalReviews\UI\Wordpress\Admin;

use HexagonalReviews\Review\Application\Query\Get\GetHandler;
use HexagonalReviews\Review\Application\Query\Get\GetQuery;
use HexagonalReviews\Review\Domain\Exception\IncorrectStars;
use HexagonalReviews\Review\Domain\Exception\ReviewNotFound;
use HexagonalReviews\Review\Domain\Exception\StatusNotFound;
use HexagonalReviews\Shared\Infrastructure\DependencyInjection\Container;
use function add_submenu_page;

/**
 * Class EditReviewController
 *
 * @package HexagonalReviews\UI\Wordpress\Admin
 */
class EditReviewController {

	/**
	 * Handler
	 *
	 * @var GetHandler
	 */
	private $get_handler;

	/**
	 * EditReviewController constructor.
	 */
	public function __construct() {
		$container         = new Container();
		$this->get_handler = $container->get( GetHandler::class );
	}

	/**
	 * Run
	 */
	public function run() {
		add_submenu_page( null, __( 'Edit Review', 'hexagonal-reviews' ), __( 'Edit Review', 'hexagonal-reviews' ), 'manage_options', 'edit-review', array( $this, 'load' ), 999 );
	}

	/**
	 * Load
	 *
	 * @throws IncorrectStars IncorrectStars.
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws StatusNotFound StatusNotFound.
	 */
	public function load() {
		if ( isset( $_REQUEST['_wpnonce'], $_REQUEST['uuid'] ) && check_admin_referer( 'edit-review', '_wpnonce' ) && wp_verify_nonce( sanitize_key( $_REQUEST['_wpnonce'] ), 'edit-review' ) ) {
			$review = $this->get_handler->run(
				new GetQuery(
					sanitize_text_field( wp_unslash( $_REQUEST['uuid'] ) )
				)
			);
		}

		include 'templates/EditReview.php';
	}
}
