<?php
/**
 * ListReviewsByPostController
 *
 * @package UI
 */

declare( strict_types=1 );

namespace HexagonalReviews\UI\Wordpress\Admin;

use function add_menu_page;

/**
 * Class ListReviewsByPostController
 *
 * @package HexagonalReviews\UI\Wordpress\Admin
 */
class ListReviewsByPostController {

	/**
	 * Run
	 */
	public function run() {
		add_menu_page( 'Reviews by Post', 'Reviews by Post', 'manage_options', 'reviews-by-post', array( $this, 'load' ), 'dashicons-chart-pie', 999 );
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
		include 'templates/ListReviewsByPost.php';
	}
}
