<?php

declare( strict_types=1 );

namespace BetterReview\UI\Wordpress\Admin;

use BetterReview\Review\Application\Command\Create\CreateCommand;
use BetterReview\Review\Application\Command\Create\CreateHandler;
use BetterReview\Review\Application\Command\Update\UpdateCommand;
use BetterReview\Review\Application\Command\Update\UpdateHandler;
use BetterReview\Review\Domain\Exception\IncorrectStars;
use BetterReview\Review\Domain\Exception\ReviewNotFound;
use BetterReview\Review\Domain\Exception\StatusNotFound;
use BetterReview\Shared\Infrastructure\DependencyInjection\Container;

/**
 * Class SaveReviewController
 *
 * @package BetterReview\UI\Wordpress\Admin
 */
class SaveReviewController {
	/**
	 * Create
	 *
	 * @var CreateHandler
	 */
	private $create_handler;

	/**
	 * Update
	 *
	 * @var UpdateHandler
	 */
	private $update_handler;

	/**
	 * SaveReviewController constructor.
	 */
	public function __construct() {
		$this->create_handler = Container::resolve( CreateHandler::class );
		$this->update_handler = Container::resolve( UpdateHandler::class );
	}

	/**
	 * Run
	 */
	public function run(): void {
		if ( isset( $_POST['page'] ) && 'save-review' === $_POST['page'] ) {
			$this->save();

			wp_safe_redirect( admin_url( '/admin.php?page=reviews', 'admin' ), 301 );
			exit;
		}

	}

	/**
	 * Save
	 *
	 * @throws IncorrectStars IncorrectStars.
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws StatusNotFound StatusNotFound.
	 */
	private function save(): void {
		if ( ! empty( $_POST['uuid'] ) ) {
			$this->update_handler->run(
				new UpdateCommand(
					esc_attr( $_POST['uuid'] ),
					(int) esc_attr( $_POST['post_id'] ),
					esc_attr( $_POST['status'] ),
					esc_attr( $_POST['author'] ),
					esc_attr( $_POST['title'] ),
					esc_attr( $_POST['content'] ),
					esc_attr( $_POST['email'] ),
					(float) esc_attr( $_POST['stars'] )
				)
			);

			return;
		}

		$this->create_handler->run(
			new CreateCommand(
				(int) esc_attr( $_POST['post_id'] ),
				esc_attr( $_POST['author'] ),
				esc_attr( $_POST['title'] ),
				esc_attr( $_POST['content'] ),
				esc_attr( $_POST['email'] ),
				(float) esc_attr( $_POST['stars'] )
			)
		);
	}
}
