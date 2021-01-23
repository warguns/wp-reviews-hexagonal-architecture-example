<?php
/**
 * CreateShortcutController
 *
 * @package UI
 */

declare( strict_types=1 );

namespace HexagonalReviews\UI\Wordpress\Admin;

use HexagonalReviews\Review\Application\Command\Delete\DeleteCommand;
use HexagonalReviews\Review\Application\Command\Delete\DeleteHandler;
use HexagonalReviews\Review\Application\Query\All\ListHandler;
use HexagonalReviews\Review\Application\Query\All\ListQuery;
use HexagonalReviews\Review\Domain\Exception\IncorrectStars;
use HexagonalReviews\Review\Domain\Exception\ReviewNotFound;
use HexagonalReviews\Review\Domain\Exception\StatusNotFound;
use HexagonalReviews\Review\Domain\Repository\ReviewRepository;
use HexagonalReviews\Shared\Infrastructure\DependencyInjection\Container;
use HexagonalReviews\Shared\Infrastructure\Wordpress\ReviewsAdminTable;
use WP_Post;
use WP_Query;
use function add_menu_page;

/**
 * Class CreateShortcutController
 *
 * @package HexagonalReviews\UI\Wordpress\Admin
 */
class CreateShortcutController {
	/**
	 * Run
	 */
	public function run() {
		add_menu_page( 'Reviews Shortcut Generator', 'Reviews Shortcut Generator', 'manage_options', 'reviews-shortcut-generator', array( $this, 'load' ), 'dashicons-chart-pie', 999 );
	}

	/**
	 * Load
	 */
	public function load() {
		$this->render();
	}

	/**
	 * Render
	 */
	private function render(): void {
		include 'templates/ShortcutGenerator.php';
	}
}
