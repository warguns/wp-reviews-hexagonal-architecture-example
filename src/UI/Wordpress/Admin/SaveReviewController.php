<?php
/**
 * SaveReviewController
 *
 * @package UI
 */

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
		$container            = new Container();
		$this->create_handler = $container->get( CreateHandler::class );
		$this->update_handler = $container->get( UpdateHandler::class );
	}

	/**
	 * Run
	 *
	 * @throws IncorrectStars IncorrectStars.
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws StatusNotFound StatusNotFound.
	 */
	public function run(): void {
		$saved = $this->save();
		if ( $saved ) {
			wp_safe_redirect( admin_url( '/admin.php?page=reviews', 'admin' ), 301 );
			exit;
		}
	}

	/**
	 * Save.
	 *
	 * @return bool
	 * @throws IncorrectStars IncorrectStars.
	 * @throws ReviewNotFound ReviewNotFound.
	 * @throws StatusNotFound StatusNotFound.
	 */
	private function save(): bool {

		if ( isset( $_POST['post_id'], $_POST['status'], $_POST['author'], $_POST['title'], $_POST['content'], $_POST['email'], $_POST['stars'], $_POST['page'], $_REQUEST['edit-review'] ) && 'save-review' === $_POST['page'] && check_admin_referer( 'edit-review', 'edit-review' ) && wp_verify_nonce( sanitize_key( $_REQUEST['edit-review'] ), 'edit-review' ) ) {
			if ( ! empty( $_POST['uuid'] ) ) {
				$this->update_handler->run(
					new UpdateCommand(
						sanitize_text_field( wp_unslash( $_POST['uuid'] ) ),
						(int) sanitize_text_field( wp_unslash( $_POST['post_id'] ) ),
						sanitize_text_field( wp_unslash( $_POST['status'] ) ),
						sanitize_text_field( wp_unslash( $_POST['author'] ) ),
						sanitize_text_field( wp_unslash( $_POST['title'] ) ),
						sanitize_text_field( wp_unslash( $_POST['content'] ) ),
						sanitize_text_field( wp_unslash( $_POST['email'] ) ),
						(float) sanitize_text_field( wp_unslash( $_POST['stars'] ) )
					)
				);

				return true;
			}

			$this->create_handler->run(
				new CreateCommand(
					(int) sanitize_text_field( wp_unslash( $_POST['post_id'] ) ),
					sanitize_text_field( wp_unslash( $_POST['author'] ) ),
					sanitize_text_field( wp_unslash( $_POST['title'] ) ),
					sanitize_text_field( wp_unslash( $_POST['content'] ) ),
					sanitize_text_field( wp_unslash( $_POST['email'] ) ),
					(float) sanitize_text_field( wp_unslash( $_POST['stars'] ) )
				)
			);

			return true;
		}

		return false;
	}
}
