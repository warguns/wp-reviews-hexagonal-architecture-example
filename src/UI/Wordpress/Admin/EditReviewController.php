<?php

declare( strict_types=1 );

namespace BetterReview\UI\Wordpress\Admin;

use BetterReview\Review\Application\Query\Get\GetHandler;
use BetterReview\Review\Application\Query\Get\GetQuery;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;
use function add_submenu_page;

/**
 * Class EditReviewController
 *
 * @package BetterReview\UI\Wordpress\Admin
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
		$this->get_handler = Container::resolve( GetHandler::class );
	}

	/**
	 * Run
	 */
	public function run() {
		add_submenu_page( null, __( 'Edit Review', 'better-reviews' ), __( 'Edit Review', 'better-reviews' ), 'manage_options', 'edit-review', array( $this, 'load' ), 999 );
	}

	/**
	 * Load
	 *
	 * @throws IncorrectStars IncorrectStars.
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws StatusNotFound StatusNotFound.
	 */
	public function load() {
		if ( isset( $_REQUEST['uuid'] ) ) {
			$review = $this->get_handler->run(
				new GetQuery(
					esc_attr( $_REQUEST['uuid'] )
				)
			);
		}

		include 'templates/EditReview.php';
	}

	/**
	 * Style Loader
	 */
	public function load_styles() {
		wp_enqueue_style( 'admin-styles', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' );
	}
}
